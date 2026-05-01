<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @if($post->type === 'found')
                        Claims Management
                    @else
                        Found Notifications Management
                    @endif
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    For: "{{ Str::limit($post->title, 50) }}"
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('posts.show', $post) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Post
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Post Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        @if($post->images)
                            <img src="{{ url('storage/' . $post->images) }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                        @endif
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $post->title }}</h3>
                            <div class="flex space-x-2 mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                           {{ $post->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($post->type) }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($post->category) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ Str::limit($post->description, 150) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">
                            @if($post->type === 'found')
                                All Claims ({{ $post->claims->total() ?? $post->claims->count() }})
                            @else
                                All Found Notifications ({{ $post->foundNotifications->total() ?? $post->foundNotifications->count() }})
                            @endif
                        </h3>
                        
                        <form method="GET" action="{{ route('posts.claims-and-found', $post) }}" class="flex items-center space-x-2">
                            <label for="sort" class="text-sm font-medium text-gray-700">Sort by:</label>
                            <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="latest" {{ ($sortBy ?? 'latest') === 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ ($sortBy ?? '') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            @if($post->type === 'found')
                                Claims Management
                            @else
                                Found Notifications Management
                            @endif
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>
                                @if($post->type === 'found')
                                    Review all claims for your found item. When you accept one claim, all other pending claims will be automatically rejected.
                                @else
                                    Review all found notifications for your lost item. When you accept one notification, all other pending notifications will be automatically rejected.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($post->type === 'found')
                <!-- Claims Section -->
                @if($post->claims->count() > 0)
                    <div class="space-y-4">
                        @foreach($post->claims as $claim)
                            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-l-4 {{ $claim->status === 'accepted' ? 'border-green-500' : ($claim->status === 'rejected' ? 'border-red-500' : 'border-yellow-500') }}">
                                <div class="p-6">
                                    <!-- Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center space-x-4">
                                            <x-user-avatar :user="$claim->user" size="lg" />
                                            <div>
                                                <h4 class="text-xl font-bold text-gray-900">{{ $claim->user->name }}</h4>
                                                <p class="text-sm text-gray-500 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Submitted {{ $claim->created_at->format('M d, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                                   {{ $claim->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                                      ($claim->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($claim->status) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.456-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                                                </svg>
                                                Claim Message
                                            </h5>
                                            <div class="bg-gray-50 p-4 rounded-lg border">
                                                <p class="text-gray-700 leading-relaxed">{{ $claim->message }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($claim->contact_info)
                                            <div>
                                                <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    Contact Information
                                                </h5>
                                                <div class="bg-gray-50 p-4 rounded-lg border">
                                                    <p class="text-gray-700 font-medium">{{ $claim->contact_info }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="border-t pt-6">
                                        @if($claim->status === 'pending')
                                            <div class="flex flex-col sm:flex-row gap-3 mb-4">
                                                <form method="POST" action="{{ route('claims.accept', $claim) }}" onsubmit="return false;" class="flex-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" 
                                                            onclick="confirmAcceptClaim(this.form, '{{ addslashes($claim->user->name) }}')"
                                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Accept This Claim
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('claims.reject', $claim) }}" onsubmit="return false;" class="flex-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" 
                                                            onclick="confirmRejectClaim(this.form, '{{ addslashes($claim->user->name) }}')"
                                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Reject This Claim
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($claim->status === 'accepted')
                                            <div class="flex items-center justify-center text-green-700 mb-4 p-3 bg-green-50 rounded-lg">
                                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold text-lg">This claim has been accepted</span>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center text-red-700 mb-4 p-3 bg-red-50 rounded-lg">
                                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold text-lg">This claim has been rejected</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Message Button -->
                                        <div class="flex justify-center">
                                            <a href="{{ route('messages.show', [$post, $claim->user]) }}" 
                                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.456-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                                                </svg>
                                                Send Message to {{ $claim->user->name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Pagination -->
                        @if(method_exists($post->claims, 'hasPages') && $post->claims->hasPages())
                            <div class="mt-8">
                                {{ $post->claims->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No claims yet</h3>
                            <p class="text-gray-500">No one has claimed this item yet.</p>
                        </div>
                    </div>
                @endif
            @else
                <!-- Found Notifications Section -->
                @if($post->foundNotifications && $post->foundNotifications->count() > 0)
                    <div class="space-y-4">
                        @foreach($post->foundNotifications as $foundNotification)
                            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-l-4 {{ $foundNotification->status === 'accepted' ? 'border-green-500' : ($foundNotification->status === 'rejected' ? 'border-red-500' : 'border-orange-500') }}">
                                <div class="p-6">
                                    <!-- Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center space-x-4">
                                            <x-user-avatar :user="$foundNotification->finder" size="lg" />
                                            <div>
                                                <h4 class="text-xl font-bold text-gray-900">{{ $foundNotification->finder->name }}</h4>
                                                <p class="text-sm text-gray-500 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Submitted {{ $foundNotification->created_at->format('M d, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                                   {{ $foundNotification->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                                      ($foundNotification->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800') }}">
                                            {{ ucfirst($foundNotification->status) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="grid md:grid-cols-3 gap-6 mb-6">
                                        <div>
                                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.456-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                                                </svg>
                                                Message
                                            </h5>
                                            <div class="bg-gray-50 p-4 rounded-lg border">
                                                <p class="text-gray-700 leading-relaxed">{{ $foundNotification->message }}</p>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Found Location
                                            </h5>
                                            <div class="bg-gray-50 p-4 rounded-lg border">
                                                <p class="text-gray-700 font-medium">{{ $foundNotification->found_location }}</p>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                Contact Information
                                            </h5>
                                            <div class="bg-gray-50 p-4 rounded-lg border">
                                                <p class="text-gray-700 font-medium">{{ $foundNotification->contact_info }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="border-t pt-6">
                                        @if($foundNotification->status === 'pending')
                                            <div class="flex flex-col sm:flex-row gap-3 mb-4">
                                                <form method="POST" action="{{ route('found-notifications.accept', $foundNotification) }}" onsubmit="return false;" class="flex-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" 
                                                            onclick="confirmAcceptFoundNotification(this.form, '{{ addslashes($foundNotification->finder->name) }}')"
                                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Accept This Notification
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('found-notifications.reject', $foundNotification) }}" onsubmit="return false;" class="flex-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" 
                                                            onclick="confirmRejectFoundNotification(this.form, '{{ addslashes($foundNotification->finder->name) }}')"
                                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Reject This Notification
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($foundNotification->status === 'accepted')
                                            <div class="flex items-center justify-center text-green-700 mb-4 p-3 bg-green-50 rounded-lg">
                                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold text-lg">This notification has been accepted</span>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center text-red-700 mb-4 p-3 bg-red-50 rounded-lg">
                                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold text-lg">This notification has been rejected</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Message Button -->
                                        <div class="flex justify-center">
                                            <a href="{{ route('messages.show', [$post, $foundNotification->finder]) }}" 
                                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.456-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                                                </svg>
                                                Send Message to {{ $foundNotification->finder->name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Pagination -->
                        @if(method_exists($post->foundNotifications, 'hasPages') && $post->foundNotifications->hasPages())
                            <div class="mt-8">
                                {{ $post->foundNotifications->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-12 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No found notifications yet</h3>
                            <p class="text-gray-500">No one has reported finding this item yet.</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <script>
        function confirmAcceptClaim(form, userName) {
            confirmationSystem.showConfirmation({
                title: 'Accept Claim',
                message: `Are you sure you want to accept the claim from ${userName}? This will automatically reject all other pending claims and mark the post as resolved.`,
                confirmText: 'Accept Claim',
                confirmClass: 'bg-green-500 hover:bg-green-700',
                icon: 'success',
                onConfirm: () => {
                    form.submit();
                }
            });
        }

        function confirmRejectClaim(form, userName) {
            confirmationSystem.showConfirmation({
                title: 'Reject Claim',
                message: `Are you sure you want to reject the claim from ${userName}?`,
                confirmText: 'Reject Claim',
                confirmClass: 'bg-red-500 hover:bg-red-700',
                icon: 'warning',
                onConfirm: () => {
                    form.submit();
                }
            });
        }

        function confirmAcceptFoundNotification(form, userName) {
            confirmationSystem.showConfirmation({
                title: 'Accept Found Notification',
                message: `Are you sure you want to accept the found notification from ${userName}? This will automatically reject all other pending notifications and mark the post as resolved.`,
                confirmText: 'Accept Notification',
                confirmClass: 'bg-green-500 hover:bg-green-700',
                icon: 'success',
                onConfirm: () => {
                    form.submit();
                }
            });
        }

        function confirmRejectFoundNotification(form, userName) {
            confirmationSystem.showConfirmation({
                title: 'Reject Found Notification',
                message: `Are you sure you want to reject the found notification from ${userName}?`,
                confirmText: 'Reject Notification',
                confirmClass: 'bg-red-500 hover:bg-red-700',
                icon: 'warning',
                onConfirm: () => {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>