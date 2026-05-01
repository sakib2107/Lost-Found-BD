<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Advanced Filter Dropdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <!-- Main Search Bar -->
                    <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between mb-4">
                        <form method="GET" action="{{ route('messages.index') }}" class="flex-1 flex gap-2">
                            <div class="flex-1">
                                <input type="text" name="search" id="search" value="{{ $search }}" 
                                       placeholder="Search by name or post title..." 
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
                        <form method="GET" action="{{ route('messages.index') }}" class="space-y-4">
                            <!-- Preserve search term -->
                            <input type="hidden" name="search" value="{{ $search }}">
                            
                            <!-- Filter Dropdowns Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Filter -->
                                <div class="relative">
                                    <select name="filter" id="filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>📧 All Messages</option>
                                        <option value="unread" {{ $filter === 'unread' ? 'selected' : '' }}>🔴 Unread Only</option>
                                        <option value="read" {{ $filter === 'read' ? 'selected' : '' }}>✅ Read Only</option>
                                    </select>
                                </div>

                                <!-- Sort -->
                                <div class="relative">
                                    <select name="sort" id="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>⏰ Latest First</option>
                                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>📅 Oldest First</option>
                                        <option value="unread_first" {{ $sort === 'unread_first' ? 'selected' : '' }}>🔴 Unread First</option>
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
                                <a href="{{ route('messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                    Clear All
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Active Filters Display -->
                    @if($search || $filter !== 'all' || $sort !== 'latest')
                        <div class="mt-4 pt-4 border-t">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm text-gray-600">Active filters:</span>
                                
                                @if($filter !== 'all')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Filter: {{ ucfirst($filter) }}
                                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                                    </span>
                                @endif

                                @if($sort !== 'latest')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Sort: {{ str_replace('_', ' ', ucfirst($sort)) }}
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="ml-1 text-green-600 hover:text-green-800">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($conversations->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="divide-y divide-gray-200">
                        @foreach($conversations as $conversation)
                            <div class="p-6 hover:bg-gray-50">
                                <a href="{{ route('messages.show', [$conversation->post, $conversation->other_user]) }}" class="block">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <x-user-avatar :user="$conversation->other_user" size="md" />
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $conversation->other_user->name }}
                                                        </p>
                                                        @if($conversation->unread_count > 0)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ $conversation->unread_count }} new
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        Re: {{ $conversation->post->title }}
                                                    </p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ \Carbon\Carbon::parse($conversation->last_message_at)->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                       {{ $conversation->post->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($conversation->post->type) }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No conversations yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start a conversation by messaging someone about their post.</p>
                        <div class="mt-6">
                            <a href="{{ route('posts.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Browse Posts
                            </a>
                        </div>
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
            @if($search || $filter !== 'all' || $sort !== 'latest')
                filterPanel.classList.remove('hidden');
                filterChevron.style.transform = 'rotate(180deg)';
            @endif
        });
    </script>
</x-app-layout>