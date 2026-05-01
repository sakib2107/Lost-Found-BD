<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage My Posts') }}
            </h2>
            <a href="{{ route('posts.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Advanced Filter Dropdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <!-- Main Search Bar -->
                    <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between mb-4">
                        <form method="GET" action="{{ route('posts.my-posts') }}" class="flex-1 flex gap-2">
                            <div class="flex-1">
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       placeholder="Search title, description, location..." 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded whitespace-nowrap">
                                Search
                            </button>
                        </form>
                        
                        <!-- Filter Toggle Button -->
                        <button type="button" id="filterToggle" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md border border-gray-300 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                            </svg>
                            Filters
                            <svg id="filterChevron" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Collapsible Filter Panel -->
                    <div id="filterPanel" class="hidden border-t pt-4">
                        <form method="GET" action="{{ route('posts.my-posts') }}" class="space-y-4">
                            <!-- Preserve search term -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            
                            <!-- Filter Dropdowns Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                                <!-- Status Filter -->
                                <div class="relative">
                                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Active</option>
                                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>✅ Resolved</option>
                                    </select>
                                </div>

                                <!-- Type Filter -->
                                <div class="relative">
                                    <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                        <option value="">All Types</option>
                                        <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>🔍 Lost</option>
                                        <option value="found" {{ request('type') === 'found' ? 'selected' : '' }}>✅ Found</option>
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

                                <!-- Sort -->
                                <div class="relative">
                                    <select name="sort" id="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>⏰ Latest</option>
                                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>📅 Oldest</option>
                                        <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>🔤 Title</option>
                                        <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>📊 Status</option>
                                    </select>
                                </div>

                                <!-- Placeholder for alignment -->
                                <div></div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    Apply Filters
                                </button>
                                <a href="{{ route('posts.my-posts') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    Clear All
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['status', 'type', 'category', 'sort']))
                        <div class="mt-4 pt-4 border-t">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm text-gray-600">Active filters:</span>
                                
                                @if(request('status'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Status: {{ ucfirst(request('status')) }}
                                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif

                                @if(request('type'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Type: {{ ucfirst(request('type')) }}
                                        <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif

                                @if(request('category'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Category: {{ ucfirst(request('category')) }}
                                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">×</a>
                                    </span>
                                @endif

                                @if(request('sort') && request('sort') !== 'latest')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Sort: {{ ucfirst(request('sort')) }}
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="ml-1 text-yellow-600 hover:text-yellow-800">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Posts Management -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($posts->count() > 0)
                        <!-- Bulk Actions -->
                        <div class="mb-6 flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Select All</span>
                                </label>
                                <button type="button" 
                                        id="bulk-delete-btn" 
                                        onclick="handleBulkDelete()"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                    Delete Selected
                                </button>
                                <span id="selected-count" class="text-sm text-gray-600">0 selected</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                Total: {{ $posts->total() }} posts
                            </div>
                        </div>

                        <!-- Posts List -->
                        <form id="bulk-delete-form" method="POST" action="{{ route('posts.bulk-delete') }}">
                            @csrf
                            @method('DELETE')
                            
                            <div class="space-y-4">
                                @foreach($posts as $post)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex items-start space-x-4">
                                            <!-- Checkbox -->
                                            <div class="flex-shrink-0 pt-1">
                                                <input type="checkbox" 
                                                       name="post_ids[]" 
                                                       value="{{ $post->id }}" 
                                                       class="post-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>

                                            <!-- Post Content -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h4 class="font-medium text-gray-900 truncate">
                                                        <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600">
                                                            {{ $post->title }}
                                                        </a>
                                                    </h4>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                               {{ $post->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ ucfirst($post->type) }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                               {{ $post->status === 'resolved' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $post->status)) }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ ucfirst($post->category) }}
                                                    </span>
                                                </div>
                                                
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($post->description, 150) }}</p>
                                                
                                                <div class="flex items-center text-xs text-gray-500 space-x-4">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $post->location }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $post->created_at->diffForHumans() }}
                                                    </span>
                                                    @if($post->type === 'found')
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                            </svg>
                                                            {{ $post->claims->count() }} claims
                                                        </span>
                                                    @else
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l6.586 6.586a2 2 0 002.828 0l6.586-6.586A2 2 0 0019.414 5H4.828a2 2 0 00-1.414 2z"></path>
                                                            </svg>
                                                            {{ $post->foundNotifications ? $post->foundNotifications->count() : 0 }} found notifications
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex-shrink-0 flex space-x-2">
                                                <a href="{{ route('posts.show', $post) }}" 
                                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    View
                                                </a>
                                                <a href="{{ route('posts.edit', $post) }}" 
                                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Edit
                                                </a>
                                                <button type="button" 
                                                        onclick="confirmDeletePost({{ $post->id }}, '{{ addslashes($post->title) }}')"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $posts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.5-.816-6.207-2.175.168-.288.336-.576.504-.864C7.798 10.64 9.798 10 12 10s4.202.64 5.703 1.961c.168.288.336.576.504.864A7.962 7.962 0 0112 15z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No posts found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request()->hasAny(['search', 'status', 'type', 'category']))
                                    Try adjusting your filters or <a href="{{ route('posts.my-posts') }}" class="text-blue-600 hover:text-blue-900">clear all filters</a>.
                                @else
                                    Get started by creating your first post.
                                @endif
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('posts.create') }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Create Post
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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
            @if(request()->hasAny(['status', 'type', 'category', 'sort']))
                filterPanel.classList.remove('hidden');
                filterChevron.style.transform = 'rotate(180deg)';
            @endif
        });

        // Select All functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        // Individual checkbox functionality
        document.querySelectorAll('.post-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.post-checkbox');
            const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const selectedCount = document.getElementById('selected-count');
            const selectAll = document.getElementById('select-all');

            // Update selected count
            selectedCount.textContent = `${checkedBoxes.length} selected`;

            // Enable/disable bulk delete button
            bulkDeleteBtn.disabled = checkedBoxes.length === 0;

            // Update select all checkbox state
            if (checkedBoxes.length === 0) {
                selectAll.indeterminate = false;
                selectAll.checked = false;
            } else if (checkedBoxes.length === checkboxes.length) {
                selectAll.indeterminate = false;
                selectAll.checked = true;
            } else {
                selectAll.indeterminate = true;
                selectAll.checked = false;
            }
        }

        // Bulk delete functionality
        function handleBulkDelete() {
            const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
            if (checkedBoxes.length === 0) return;
            
            confirmBulkDelete(checkedBoxes.length);
        }
    </script>
</x-app-layout>