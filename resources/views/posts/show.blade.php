<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            @can('update', $post)
                <div class="flex space-x-2">
                    <a href="{{ route('posts.edit', $post) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                    <button type="button" 
                            onclick="confirmDeletePost({{ $post->id }}, '{{ addslashes($post->title) }}')"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Post Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Post Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                       {{ $post->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($post->type) }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($post->category) }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                   {{ $post->status === 'resolved' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $post->status)) }}
                        </span>
                    </div>

                    <!-- Images -->
                    @if($post->images)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Image</h3>
                            <div class="relative">
                                <img src="{{ url('storage/' . $post->images) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full max-h-96 object-contain rounded-lg cursor-pointer bg-gray-50"
                                     onclick="openImageModal('{{ url('storage/' . $post->images) }}')"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500" style="display: none;">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm">Image not available</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $post->description }}</p>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Location</h4>
                            <p class="text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $post->location }}
                            </p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Date {{ $post->type === 'lost' ? 'Lost' : 'Found' }}</h4>
                            <p class="text-gray-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v8a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z"></path>
                                </svg>
                                {{ $post->date_lost_found->format('F d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Posted By -->
                    <div class="border-t pt-4">
                        <div class="flex items-center space-x-3">
                            <x-user-avatar :user="$post->user" size="sm" />
                            <div>
                                <p class="text-sm text-gray-500">
                                    Posted by <span class="font-medium text-gray-900">{{ $post->user->name }}</span>
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $post->created_at->format('F d, Y \a\t g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Update (for post owner) -->
            @can('update', $post)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                        <form method="POST" action="{{ route('posts.update-status', $post) }}" class="flex items-center space-x-4" onsubmit="return false;">
                            @csrf
                            @method('PATCH')
                            <select name="status" id="statusSelect" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="active" {{ $post->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="resolved" {{ $post->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                            <button type="button" 
                                    onclick="confirmStatusUpdate(this.form, '{{ addslashes($post->title) }}', document.getElementById('statusSelect').value, '{{ $post->status }}')"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
            @endcan

            <!-- Claims and Found Management (for post owner) -->
            @can('update', $post)
                @if(($post->type === 'found' && $post->claims->count() > 0) || ($post->type === 'lost' && $post->foundNotifications && $post->foundNotifications->count() > 0))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        @if($post->type === 'found')
                                            Claims Management
                                        @else
                                            Found Notifications Management
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        @if($post->type === 'found')
                                            You have {{ $post->claims->count() }} claim(s) for this item
                                        @else
                                            You have {{ $post->foundNotifications->count() }} found notification(s) for this item
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('posts.claims-and-found', $post) }}" 
                                   class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                    @if($post->type === 'found')
                                        Show Claims
                                    @else
                                        Show Found Notifications
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            <!-- Action Buttons -->
            @auth
                @if($post->user_id !== auth()->id())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-wrap gap-4">
                                <!-- Claim Button (only for found items) -->
                                @if($post->type === 'found' && $post->status === 'active')
                                    <button onclick="openClaimModal()" 
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Claim
                                    </button>
                                @endif

                                <!-- Found Notification Button (only for lost items) -->
                                @if($post->type === 'lost' && $post->status === 'active')
                                    <button onclick="openFoundModal()" 
                                            class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                        Found
                                    </button>
                                @endif

                                <!-- Message Button -->
                                <a href="{{ route('messages.create', $post) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Send Message
                                </a>

                                                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            
            <!-- Comments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Comments ({{ $post->comments->count() }})</h3>
                    
                    <!-- Add Comment Form -->
                    @auth
                        <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                            @csrf
                            <div class="flex space-x-4">
                                <textarea name="message" rows="3" placeholder="Add a comment..." required
                                          class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Post
                                </button>
                            </div>
                        </form>
                    @endauth

                    <!-- Comments List -->
                    @if($post->comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($post->comments as $comment)
                                <div class="flex space-x-3" id="comment-{{ $comment->id }}">
                                    <x-user-avatar :user="$comment->user" size="sm" class="flex-shrink-0" />
                                    <div class="flex-1">
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $comment->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->id() === $post->user_id))
                                                    <div class="flex items-center space-x-2">
                                                        @if(auth()->id() === $comment->user_id)
                                                            <button type="button" 
                                                                    onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->message) }}')"
                                                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center space-x-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                                <span>Edit</span>
                                                            </button>
                                                        @endif
                                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return false;" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" 
                                                                    onclick="confirmDeleteComment(this.form)"
                                                                    class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center space-x-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                                <span>Delete</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="comment-content">
                                                <p class="text-gray-700 text-sm">{{ $comment->message }}</p>
                                            </div>
                                            <div class="comment-edit-form hidden">
                                                <form method="POST" action="{{ route('comments.update', $comment) }}" onsubmit="return false;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <textarea name="message" rows="3" required
                                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $comment->message }}</textarea>
                                                    <div class="flex justify-end space-x-2 mt-2">
                                                        <button type="button" 
                                                                onclick="cancelEdit({{ $comment->id }})"
                                                                class="px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-800">
                                                            Cancel
                                                        </button>
                                                        <button type="button" 
                                                                onclick="confirmUpdateComment(this.form, {{ $comment->id }})"
                                                                class="px-3 py-1 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded">
                                                            Save
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Claim Modal (for found items) -->
    @if($post->type === 'found' && $post->status === 'active' && auth()->check() && $post->user_id !== auth()->id())
        <div id="claimModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Claim</h3>
                    <form method="POST" action="{{ route('claims.store', $post) }}" onsubmit="return false;">
                        @csrf
                        <div class="mb-4">
                            <label for="claim_message" class="block text-sm font-medium text-gray-700">Why is this yours?</label>
                            <textarea name="message" id="claim_message" rows="4" required
                                      placeholder="Describe why you believe this item belongs to you..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="contact_info" class="block text-sm font-medium text-gray-700">Contact Info *</label>
                            <input type="text" name="contact_info" id="contact_info" required
                                   placeholder="Phone number or email to reach you"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="closeClaimModal()" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                            <button type="button" 
                                    onclick="confirmClaim(this.form, '{{ addslashes($post->title) }}')"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Submit Claim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Found Modal (for lost items) -->
    @if($post->type === 'lost' && $post->status === 'active' && auth()->check() && $post->user_id !== auth()->id())
        <div id="foundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Found</h3>
                    <p class="text-sm text-gray-600 mb-4">Let the owner know you found their item. This will send them a notification.</p>
                    <form method="POST" action="{{ route('found-notifications.store', $post) }}" onsubmit="return false;">
                        @csrf
                        <div class="mb-4">
                            <label for="found_message" class="block text-sm font-medium text-gray-700">Message to Owner</label>
                            <textarea name="message" id="found_message" rows="4" required
                                      placeholder="Hi! I think I found your item. Let me know if this matches what you lost..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="found_location" class="block text-sm font-medium text-gray-700">Where did you find it?</label>
                            <input type="text" name="found_location" id="found_location" required
                                   placeholder="Location where you found the item"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4">
                            <label for="found_contact" class="block text-sm font-medium text-gray-700">Your Contact Info</label>
                            <input type="text" name="contact_info" id="found_contact" required
                                   placeholder="Phone number or email to reach you"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="closeFoundModal()" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </button>
                            <button type="button" 
                                    onclick="confirmFoundNotification(this.form, '{{ addslashes($post->title) }}')"
                                    class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                Notify Owner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-auto shadow-lg rounded-md bg-white max-w-4xl">
            <div class="flex justify-end">
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <img id="modalImage" src="" alt="" class="w-full h-auto">
        </div>
    </div>

    <script>
        function openClaimModal() {
            document.getElementById('claimModal').classList.remove('hidden');
        }

        function closeClaimModal() {
            document.getElementById('claimModal').classList.add('hidden');
        }

        function openFoundModal() {
            document.getElementById('foundModal').classList.remove('hidden');
        }

        function closeFoundModal() {
            document.getElementById('foundModal').classList.add('hidden');
        }

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Comment editing functions
        function editComment(commentId, currentMessage) {
            const commentDiv = document.getElementById('comment-' + commentId);
            const contentDiv = commentDiv.querySelector('.comment-content');
            const editForm = commentDiv.querySelector('.comment-edit-form');
            
            contentDiv.classList.add('hidden');
            editForm.classList.remove('hidden');
            
            // Focus on textarea
            const textarea = editForm.querySelector('textarea');
            textarea.focus();
            textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        }

        function cancelEdit(commentId) {
            const commentDiv = document.getElementById('comment-' + commentId);
            const contentDiv = commentDiv.querySelector('.comment-content');
            const editForm = commentDiv.querySelector('.comment-edit-form');
            
            contentDiv.classList.remove('hidden');
            editForm.classList.add('hidden');
        }

        function confirmUpdateComment(form, commentId) {
            confirmationSystem.showConfirmation({
                title: 'Update Comment',
                message: 'Are you sure you want to update this comment?',
                confirmText: 'Update',
                confirmClass: 'bg-blue-500 hover:bg-blue-700',
                icon: 'info',
                onConfirm: () => {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>