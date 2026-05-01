<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Search Results') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">AI Search Results</h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $totalFound }} {{ Str::plural('match', $totalFound) }} found
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    {{ round($threshold * 100) }}% similarity threshold
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('ai-search.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                New Search
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Applied Filters -->
                @if(isset($searchFilters) && (
                    !empty($searchFilters['category']) || 
                    !empty($searchFilters['location']) || 
                    !empty($searchFilters['date_from']) || 
                    !empty($searchFilters['date_to'])
                ))
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                            </svg>
                            <h3 class="text-sm font-semibold text-blue-900">Applied Filters:</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if(!empty($searchFilters['category']))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Category: {{ ucfirst($searchFilters['category']) }}
                                </span>
                            @endif
                            @if(!empty($searchFilters['location']))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Location: {{ $searchFilters['location'] }}
                                </span>
                            @endif
                            @if(!empty($searchFilters['date_from']))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-3a5 5 0 0110-4.9M15 8a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    From: {{ \Carbon\Carbon::parse($searchFilters['date_from'])->format('M d, Y') }}
                                </span>
                            @endif
                            @if(!empty($searchFilters['date_to']))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-3a5 5 0 0110-4.9M15 8a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    To: {{ \Carbon\Carbon::parse($searchFilters['date_to'])->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Search Image Display -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Your Search Image
                    </h2>
                    <div class="flex justify-center">
                        <div class="relative">
                            <img src="{{ Storage::url($searchImagePath) }}" alt="Search Image" class="max-w-sm max-h-64 rounded-xl shadow-lg border border-gray-200">
                            <div class="absolute -top-2 -right-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                Search Query
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Section -->
                @if($similarPosts->count() > 0)
                    <!-- Results Header -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
                        <div class="flex items-center space-x-4">
                            <h3 class="text-lg font-semibold text-gray-900">Results</h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                {{ $totalFound }} items found
                            </span>
                            <span class="text-sm text-gray-500">
                                (Sorted by similarity - highest first)
                            </span>
                        </div>
                    </div>

                    <!-- Results Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($similarPosts as $item)
                            @php
                                $post = $item['post'];
                                $similarity = $item['similarity'];
                                $similarityPercent = round($similarity * 100, 1);
                            @endphp
                            <div class="result-card bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                                
                                <!-- Image Section -->
                                <div class="relative h-48 overflow-hidden">
                                    @if($post->images)
                                        <img src="{{ Storage::url($post->images) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Similarity Badge -->
                                    <div class="absolute top-3 right-3">
                                        <div class="flex items-center space-x-1">
                                            @if($similarityPercent >= 90)
                                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    🎯 {{ $similarityPercent }}%
                                                </span>
                                            @elseif($similarityPercent >= 80)
                                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    ✨ {{ $similarityPercent }}%
                                                </span>
                                            @elseif($similarityPercent >= 70)
                                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    ⭐ {{ $similarityPercent }}%
                                                </span>
                                            @else
                                                <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    📊 {{ $similarityPercent }}%
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Post Type Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium shadow-lg {{ $post->type === 'lost' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                                            {{ $post->type === 'lost' ? '🔍 Lost' : '✅ Found' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="p-6">
                                    <!-- Title and Category -->
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1">{{ $post->title }}</h3>
                                        <span class="ml-2 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full whitespace-nowrap">
                                            {{ ucfirst($post->category) }}
                                        </span>
                                    </div>

                                    <!-- Description -->
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($post->description, 100) }}</p>

                                    <!-- Details -->
                                    <div class="space-y-2 text-sm text-gray-500 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="truncate">{{ $post->location }}</span>
                                        </div>

                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-3a5 5 0 0110-4.9M15 8a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>{{ $post->date_lost_found->format('M d, Y') }}</span>
                                        </div>

                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span>{{ $post->user->name }}</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('posts.show', $post) }}" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105">
                                            View Details
                                        </a>
                                        @auth
                                            @if($post->user_id !== auth()->id())
                                                <a href="{{ route('messages.create', $post) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                                                    Contact
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $similarPosts->links() }}
                    </div>

                @else
                    <!-- No Results -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">No Similar Posts Found</h3>
                            <p class="text-gray-600 mb-6">
                                We couldn't find any posts similar to your image with the current similarity threshold ({{ round($threshold * 100) }}%).
                            </p>
                            
                            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                                <h4 class="text-sm font-semibold text-blue-900 mb-2">Try these suggestions:</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Lower the similarity threshold to 60-70%</li>
                                    <li>• Use a different image with better lighting</li>
                                    <li>• Remove any applied filters</li>
                                    <li>• Try a closer or clearer photo</li>
                                </ul>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('ai-search.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Try Another Search
                                </a>
                                <a href="{{ route('posts.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Browse All Posts
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Search Tips -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 p-8 mt-8">
                    <h3 class="text-xl font-semibold text-blue-900 mb-6 text-center">💡 Search Tips & Similarity Guide</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                For Better Results:
                            </h4>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Use clear, well-lit images
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Focus on the main subject
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Avoid heavily filtered images
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Try different angles if needed
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Understanding Similarity:
                            </h4>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    🎯 90%+ = Excellent match
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                    ✨ 80-90% = Very similar
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                    ⭐ 70-80% = Good match
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                                    📊 60-70% = Possible match
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .result-card {
            transition: all 0.3s ease;
        }
        
        .result-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('AI Search Results page loaded');
            
            const sortSelect = document.getElementById('sortBy');
            const resultsContainer = document.getElementById('resultsContainer');
            
            if (!sortSelect || !resultsContainer) {
                console.log('Sort elements not found');
                return;
            }

            console.log('Sort functionality initialized');

            sortSelect.addEventListener('change', function() {
                const sortBy = this.value;
                console.log('Sorting by:', sortBy);
                
                const cards = Array.from(resultsContainer.children);
                console.log('Found', cards.length, 'cards to sort');

                cards.sort((a, b) => {
                    const similarityA = parseFloat(a.dataset.similarity);
                    const similarityB = parseFloat(b.dataset.similarity);
                    
                    const dateA = new Date(a.dataset.date);
                    const dateB = new Date(b.dataset.date);

                    console.log('Comparing:', {
                        cardA: { similarity: similarityA, date: dateA },
                        cardB: { similarity: similarityB, date: dateB }
                    });

                    switch(sortBy) {
                        case 'similarity-desc':
                            return similarityB - similarityA;
                        case 'similarity-asc':
                            return similarityA - similarityB;
                        case 'date-desc':
                            return dateB - dateA;
                        case 'date-asc':
                            return dateA - dateB;
                        default:
                            return 0;
                    }
                });

                // Add loading effect
                resultsContainer.style.opacity = '0.5';
                resultsContainer.style.transform = 'scale(0.98)';
                
                setTimeout(() => {
                    // Clear and re-append sorted cards
                    resultsContainer.innerHTML = '';
                    cards.forEach(card => resultsContainer.appendChild(card));
                    
                    // Remove loading effect
                    resultsContainer.style.opacity = '1';
                    resultsContainer.style.transform = 'scale(1)';
                    
                    console.log('Sorting completed');
                }, 150);
            });

            // Add smooth transitions
            resultsContainer.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

                    });
    </script>
</x-app-layout>