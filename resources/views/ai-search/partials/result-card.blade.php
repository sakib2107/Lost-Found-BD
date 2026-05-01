@php
    $post = $item['post'];
    $similarity = $item['similarity'];
    $similarityPercent = round($similarity * 100, 1);
@endphp

<div class="result-card bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]" 
     data-similarity="{{ $similarity }}" 
     data-date="{{ $post->date_lost_found->format('Y-m-d') }}"
     data-similarity-percent="{{ $similarityPercent }}">
    
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