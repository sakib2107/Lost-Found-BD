<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Notifications\MissingPersonNotification;
use App\Services\ClipEmbeddingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'claims', 'comments'])
            ->latest();

        // Apply filters
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('location')) {
            $query->byLocation($request->location);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('date_from')) {
            $query->where('date_lost_found', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date_lost_found', '<=', $request->date_to);
        }

        // Sort
        if ($request->filled('sort') && $request->sort === 'oldest') {
            $query->oldest();
        }

        $posts = $query->paginate(12);

        $categories = ['pet', 'person', 'vehicle', 'electronics', 'documents', 'jewelry', 'personal belongings', 'other'];

        return view('posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ['pet', 'person', 'vehicle', 'electronics', 'documents', 'jewelry', 'personal belongings', 'other'];
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:pet,person,vehicle,electronics,documents,jewelry,personal belongings,other',
            'location' => 'required|string|max:255',
            'date_lost_found' => 'required|date|before_or_equal:today',
            'type' => 'required|in:lost,found',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        $embedding = null;
        
        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('posts', 'public');
            
            // Generate embedding for the uploaded image
            $clipService = new ClipEmbeddingService();
            $embedding = $clipService->generateEmbedding($request->file('images'));
            
            if ($embedding) {
                Log::info('Generated embedding for new post', ['image_path' => $imagePath]);
            } else {
                Log::warning('Failed to generate embedding for new post', ['image_path' => $imagePath]);
            }
        }
  
        $validated['user_id'] = auth()->id();
        $validated['images'] = $imagePath;
        $validated['embeddings'] = $embedding;

        $post = Post::create($validated);

        // Send missing person notifications to all users if this is a person-related post
        if ($validated['category'] === 'person') {
            $notificationType = $validated['type'] === 'lost' ? 'lost' : 'found';
            
            // Get all users except the post creator
            $users = User::where('id', '!=', auth()->id())->get();
            
            foreach ($users as $user) {
                $user->notify(new MissingPersonNotification($post, $notificationType));
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'claims.user', 'foundNotifications.finder', 'comments.user', 'messages']);
        
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        
        $categories = ['pet', 'person', 'vehicle', 'electronics', 'documents', 'jewelry', 'personal belongings', 'other'];
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:pet,person,vehicle,electronics,documents,jewelry,personal belongings,other',
            'location' => 'required|string|max:255',
            'date_lost_found' => 'required|date|before_or_equal:today',
            'type' => 'required|in:lost,found',
            'status' => 'required|in:active,resolved',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $oldStatus = $post->status;
        $newStatus = $validated['status'];

        $imagePath = $post->images;
        $imageUpdated = false;
        $embedding = $post->embeddings;
        
        if ($request->hasFile('images')) {
            // Delete old image if exists
            if ($post->images) {
                Storage::disk('public')->delete($post->images);
            }
            $imagePath = $request->file('images')->store('posts', 'public');
            $imageUpdated = true;
            
            // Generate new embedding for the updated image
            $clipService = new ClipEmbeddingService();
            $embedding = $clipService->generateEmbedding($request->file('images'));
            
            if ($embedding) {
                Log::info('Generated embedding for updated post', ['post_id' => $post->id, 'image_path' => $imagePath]);
            } else {
                Log::warning('Failed to generate embedding for updated post', ['post_id' => $post->id, 'image_path' => $imagePath]);
            }
        }

        $validated['images'] = $imagePath;
        $validated['embeddings'] = $embedding;

        // If changing from resolved to active, delete all claims and found notifications
        if ($oldStatus === 'resolved' && $newStatus === 'active') {
            // Delete all claims for this post
            $post->claims()->delete();
            
            // Delete all found notifications for this post
            $post->foundNotifications()->delete();
        }

        $post->update($validated);

        $message = 'Post updated successfully!';
        if ($oldStatus === 'resolved' && $newStatus === 'active') {
            $message = 'Post updated successfully! All previous claims and found notifications have been cleared.';
        }

        return redirect()->route('posts.show', $post)->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $postTitle = $post->title;

        // Delete associated image
        if ($post->images) {
            Storage::disk('public')->delete($post->images);
        }

        // Delete the post (this will trigger the model's boot method to clean up related data)
        $post->delete();

        return redirect()->route('posts.index')->with('success', "Post '{$postTitle}' deleted successfully!");
    }

    /**
     * Update post status
     */
    public function updateStatus(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'status' => 'required|in:active,resolved'
        ]);

        $oldStatus = $post->status;
        $newStatus = $validated['status'];

        // If changing from resolved to active, delete all claims and found notifications
        if ($oldStatus === 'resolved' && $newStatus === 'active') {
            // Delete all claims for this post
            $post->claims()->delete();
            
            // Delete all found notifications for this post
            $post->foundNotifications()->delete();
        }

        $post->update($validated);

        $message = 'Status updated successfully!';
        if ($oldStatus === 'resolved' && $newStatus === 'active') {
            $message = 'Status updated successfully! All previous claims and found notifications have been cleared.';
        }

        return back()->with('success', $message);
    }

    /**
     * Display user's posts for management
     */
    public function myPosts(Request $request)
    {
        $query = auth()->user()->posts()->with(['claims', 'foundNotifications']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'status':
                $query->orderBy('status');
                break;
            default:
                $query->latest();
                break;
        }

        $posts = $query->paginate(15);
        $categories = ['pet', 'person', 'vehicle', 'electronics', 'documents', 'jewelry', 'personal belongings', 'other'];

        return view('posts.my-posts', compact('posts', 'categories'));
    }

    /**
     * Bulk delete posts
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'post_ids' => 'required|array',
            'post_ids.*' => 'exists:posts,id'
        ]);

        $posts = Post::whereIn('id', $validated['post_ids'])
                    ->where('user_id', auth()->id())
                    ->get();

        $deletedCount = 0;

        foreach ($posts as $post) {
            // Delete associated image
            if ($post->images) {
                Storage::disk('public')->delete($post->images);
            }
            $post->delete();
            $deletedCount++;
        }

        return redirect()->route('posts.my-posts')->with('success', "Successfully deleted {$deletedCount} post(s).");
    }

    /**
     * Show all claims and found notifications for a post
     */
    public function showClaimsAndFound(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        // Get sort parameter
        $sortBy = $request->get('sort', 'latest'); // latest, oldest
        
        $post->load(['user']);
        
        if ($post->type === 'found') {
            // Handle claims
            $query = $post->claims()->with('user');
            
            // Apply sorting
            if ($sortBy === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
            
            $claims = $query->paginate(10)->appends($request->query());
            $post->claims = $claims;
            
        } else {
            // Handle found notifications
            $query = $post->foundNotifications()->with('finder');
            
            // Apply sorting
            if ($sortBy === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
            
            $foundNotifications = $query->paginate(10)->appends($request->query());
            $post->foundNotifications = $foundNotifications;
        }
        
        return view('posts.claims-and-found', compact('post', 'sortBy'));
    }
}