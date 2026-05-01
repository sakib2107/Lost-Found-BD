<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <!-- Main Header Row -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Lost & Found Items') }}
                </h2>
                
                <!-- Search, AI Search, and Filters -->
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <form method="GET" action="{{ route('posts.index') }}" class="flex gap-2">
                        <div class="w-64 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   placeholder="Search items..." 
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md whitespace-nowrap transition-colors duration-200">
                            Search
                        </button>
                    </form>
                    
                    @auth
                        <a href="{{ route('ai-search.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md whitespace-nowrap flex items-center gap-2 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.091 3.091ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"></path>
                            </svg>
                            AI Search
                        </a>
                    @endauth
                    
                    <!-- Filter Toggle Button -->
                    <button type="button" id="filterToggle" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md border border-gray-300 flex items-center gap-2 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"></path>
                        </svg>
                        Filters
                        <svg id="filterChevron" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
                        </svg>
                    </button>
                    
                    @auth
                        <a href="{{ route('posts.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
                            </svg>
                            Post New Item
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Inline Filter Panel -->
            <div id="filterPanel" class="hidden border-t border-gray-200 pt-4">
                <form method="GET" action="{{ route('posts.index') }}" class="space-y-4">
                    <!-- Preserve search term -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    
                    <!-- Filter Dropdowns Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Type Filter -->
                        <div class="relative">
                            <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                <option value="">All Types</option>
                                <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>🔍 Lost Items</option>
                                <option value="found" {{ request('type') === 'found' ? 'selected' : '' }}>✅ Found Items</option>
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div class="relative">
                            <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                        @switch($category)
                                            @case('pet') 🐕 @break
                                            @case('person') 👤 @break
                                            @case('vehicle') 🚗 @break
                                            @case('electronics') 📱 @break
                                            @case('documents') 📄 @break
                                            @case('jewelry') 💍 @break
                                            @case('personal belongings') 🎒 @break
                                            @default 📦 @break
                                        @endswitch
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location Filter -->
                        <div class="relative">
                            <input type="text" name="location" id="location" value="{{ request('location') }}" 
                                   placeholder="📍 Location..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Date Range -->
                        <div class="relative">
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   title="Date From">
                        </div>

                        <div class="relative">
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   title="Date To">
                        </div>
                    </div>

                    <!-- Sort and Actions Row -->
                    <div class="flex items-center gap-4">
                        <!-- Sort Dropdown -->
                        <div class="relative">
                            <select name="sort" id="sort" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>⏰ Newest First</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>📅 Oldest First</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                Apply Filters
                            </button>
                            <a href="{{ route('posts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                Clear All
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Active Filters Display -->
                @if(request()->hasAny(['type', 'category', 'location', 'date_from', 'date_to', 'sort']))
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-gray-600">Active filters:</span>
                            
                            @if(request('type'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Type: {{ ucfirst(request('type')) }}
                                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                                </span>
                            @endif

                            @if(request('category'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Category: {{ ucfirst(request('category')) }}
                                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                </span>
                            @endif

                            @if(request('location'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Location: {{ request('location') }}
                                    <a href="{{ request()->fullUrlWithQuery(['location' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                                </span>
                            @endif

                            @if(request('date_from') || request('date_to'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Date Range
                                    <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'date_to' => null]) }}" class="ml-1 text-yellow-600 hover:text-yellow-800">×</a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-lg transition-shadow duration-200 border border-gray-100">
                            <!-- Post Image -->
                            @if($post->images)
                                <div class="h-48 bg-gray-50 flex items-center justify-center overflow-hidden">
                                    <img src="{{ url('storage/' . $post->images) }}" 
                                         alt="{{ $post->title }}" 
                                         class="max-w-full max-h-full object-contain"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400" style="display: none;">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endif

                            <div class="p-6">
                                <!-- Post Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   {{ $post->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $post->type === 'lost' ? '🔍 Lost' : '✅ Found' }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            @switch($post->category)
                                                @case('pet') 🐕 @break
                                                @case('person') 👤 @break
                                                @case('vehicle') 🚗 @break
                                                @case('electronics') 📱 @break
                                                @case('documents') 📄 @break
                                                @case('jewelry') 💍 @break
                                                @case('personal belongings') 🎒 @break
                                                @default 📦 @break
                                            @endswitch
                                            {{ ucfirst($post->category) }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                               {{ $post->status === 'resolved' ? 'bg-blue-100 text-blue-800' : 
                                                  ($post->status === 'still_missing' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $post->status)) }}
                                    </span>
                                </div>

                                <!-- Post Content -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $post->title }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($post->description, 100) }}</p>
                                
                                <!-- Post Details -->
                                <div class="text-sm text-gray-500 space-y-1 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"></path>
                                        </svg>
                                        {{ $post->location }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"></path>
                                        </svg>
                                        {{ $post->date_lost_found->format('M d, Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <x-user-avatar :user="$post->user" size="xs" class="mr-2" />
                                        {{ $post->user->name }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <a href="{{ route('posts.show', $post) }}" 
                                       class="text-blue-600 hover:text-blue-900 text-sm font-medium flex items-center gap-1">
                                        View Details
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                                        </svg>
                                    </a>
                                    <div class="flex items-center space-x-3 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"></path>
                                            </svg>
                                            {{ $post->comments->count() }}
                                        </span>
                                        @if($post->type === 'found')
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"></path>
                                                </svg>
                                                {{ $post->claims->count() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->withQueryString()->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.008H9.375V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.008h-.008V9.75Z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No items found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria or post a new item.</p>
                        @auth
                            <div class="mt-6">
                                <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Post New Item
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Filter Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterToggle = document.getElementById('filterToggle');
            const filterPanel = document.getElementById('filterPanel');
            const filterChevron = document.getElementById('filterChevron');

            filterToggle.addEventListener('click', function() {
                if (filterPanel.classList.contains('hidden')) {
                    filterPanel.classList.remove('hidden');
                    filterChevron.style.transform = 'rotate(180deg)';
                } else {
                    filterPanel.classList.add('hidden');
                    filterChevron.style.transform = 'rotate(0deg)';
                }
            });

            // Auto-expand if filters are active
            @if(request()->hasAny(['type', 'category', 'location', 'date_from', 'date_to', 'sort']))
                filterPanel.classList.remove('hidden');
                filterChevron.style.transform = 'rotate(180deg)';
            @endif
        });
    </script>
</x-app-layout>