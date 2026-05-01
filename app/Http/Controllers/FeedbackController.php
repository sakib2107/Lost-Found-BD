<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::query()->where('is_approved', true);

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('message', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }
        if ($request->filled('rating')) {
            $query->where('rating', (int)$request->rating);
        }
        if ($request->boolean('has_rating')) {
            $query->whereNotNull('rating');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating_highest':
                $query->orderByDesc('rating');
                break;
            case 'rating_lowest':
                $query->orderBy('rating');
                break;
            default:
                $query->latest();
        }

        $feedback = $query->paginate(12);

        return view('feedback.index', compact('feedback'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'rating' => 'nullable|integer|min:1|max:5',
            'message' => 'required|string|max:2000',
        ]);

        $validated['user_id'] = auth()->id();
        // Default approve; switch to false if you want moderation
        $validated['is_approved'] = true;

        Feedback::create($validated);

        return back()->with('success', 'Thanks for your feedback!');
    }

    public function edit(Feedback $feedback)
    {
        if (auth()->guest() || $feedback->user_id !== auth()->id()) {
            abort(403);
        }
        return view('feedback.edit', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        if (auth()->guest() || $feedback->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'message' => 'required|string|max:2000',
        ]);

        $feedback->update($validated);

        return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback)
    {
        if (auth()->guest() || $feedback->user_id !== auth()->id()) {
            abort(403);
        }
        $feedback->delete();
        return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
