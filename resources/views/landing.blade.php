<x-app-layout>
    <!-- Hero Section -->
    <section class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600"></div>
        <svg aria-hidden="true" class="absolute -top-10 -left-10 -z-10 h-[40rem] w-[60rem] blur-3xl opacity-30" viewBox="0 0 1155 678" fill="none">
            <path fill="url(#gradient-1)" fill-opacity=".3" d="M317.219 518.975L203.852 678 0 438.341l317.219 80.634L512.138 0 694.33 289.157 1155 357.019 839.931 678 632.607 408.219 317.219 518.975z"></path>
            <defs>
                <linearGradient id="gradient-1" x1="1155" x2="0" y1="0" y2="678" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#fff"></stop>
                    <stop offset="1" stop-color="#fff"></stop>
                </linearGradient>
            </defs>
        </svg>
        <div class="px-6 pt-12 pb-16 sm:pt-16 sm:pb-20 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-3xl text-center">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white">
                        Reunite people with their belongings, faster
                    </h1>
                    <p class="mt-4 text-base sm:text-lg leading-7 text-indigo-50">
                        Discover community-powered lost & found. Post what you lost or found, search items with AI, and connect safely to bring things back home.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('posts.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-base font-semibold text-gray-900 shadow-lg hover:bg-gray-50 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Browse Items
                        </a>
                        @auth
                        <a href="{{ route('ai-search.index') }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-3 text-base font-semibold text-white shadow-lg hover:from-purple-600 hover:to-pink-600 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.091 3.091ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"></path>
                            </svg>
                            AI Search
                        </a>
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/30 px-6 py-3 text-base font-semibold text-white hover:bg-white/20 hover:ring-white/50 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Post an Item
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/30 px-6 py-3 text-base font-semibold text-white hover:bg-white/20 hover:ring-white/50 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3 text-base font-semibold text-white shadow-lg hover:from-indigo-700 hover:to-purple-700 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Join Now
                        </a>
                        @endauth
                    </div>

                    <!-- Stats -->
                    @php
                        $activeListings = \App\Models\Post::active()->count();
                        $resolvedItems = \App\Models\Post::where('status','resolved')->count();
                        $registeredUsers = \App\Models\User::count();
                        $avgClaimHours = \App\Models\Claim::accepted()->with('post:id,created_at')->get()->map(function($c){
                            return $c->post ? $c->post->created_at->diffInHours($c->created_at) : null;
                        })->filter()->avg();
                    @endphp
                    <dl class="mt-12 grid grid-cols-3 gap-4 sm:grid-cols-3 text-left">
                        <div class="flex flex-col rounded-xl bg-white/10 p-4 ring-1 ring-white/20">
                            <dt class="text-sm text-indigo-100">Active listings</dt>
                            <dd class="mt-1 text-2xl font-bold text-white">{{ number_format($activeListings) }}</dd>
                        </div>
                        <div class="flex flex-col rounded-xl bg-white/10 p-4 ring-1 ring-white/20">
                            <dt class="text-sm text-indigo-100">Items reunited</dt>
                            <dd class="mt-1 text-2xl font-bold text-white">{{ number_format($resolvedItems) }}</dd>
                        </div>
                        <div class="flex flex-col rounded-xl bg-white/10 p-4 ring-1 ring-white/20">
                            <dt class="text-sm text-indigo-100">Registered users</dt>
                            <dd class="mt-1 text-2xl font-bold text-white">{{ number_format($registeredUsers) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Image Search Feature -->
    <section class="py-12 sm:py-16 bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <div class="inline-flex items-center rounded-full bg-gradient-to-r from-purple-100 to-pink-100 px-4 py-2 text-sm font-medium text-purple-800 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.091 3.091ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"></path>
                    </svg>
                    NEW: AI-Powered Search
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Find items with <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">AI Image Search</span>
                </h2>
                <p class="mt-4 text-lg leading-8 text-gray-600">
                    Upload a photo and let our advanced AI technology find visually similar items in our database. Perfect for when words aren't enough.
                </p>
            </div>
            
            <div class="mx-auto mt-12 grid max-w-6xl grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Feature Description -->
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">CLIP ViT-B/32 Model</h3>
                            <p class="text-gray-600">OpenAI's Vision Transformer model processes images into 512-dimensional feature vectors for accurate visual understanding.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Cosine Similarity Matching</h3>
                            <p class="text-gray-600">Mathematical similarity calculation between embeddings with adjustable threshold (10%-100%) for precise control.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Local Processing</h3>
                            <p class="text-gray-600">All AI processing happens on our server using Python CLIP implementation - no external APIs, maximum privacy.</p>
                        </div>
                    </div>
                    
                    @auth
                        <div class="pt-4">
                            <a href="{{ route('ai-search.index') }}" class="inline-flex items-center rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-3 text-base font-semibold text-white shadow-lg hover:from-purple-700 hover:to-pink-700 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.091 3.091ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"></path>
                                </svg>
                                Try AI Search Now
                            </a>
                        </div>
                    @else
                        <div class="pt-4">
                            <p class="text-sm text-gray-600 mb-4">Sign up to access AI Image Search and other advanced features</p>
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-3 text-base font-semibold text-white shadow-lg hover:from-purple-700 hover:to-pink-700 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Get Started Free
                            </a>
                        </div>
                    @endauth
                </div>
                
                <!-- Visual Demo -->
                <div class="relative">
                    <div class="rounded-2xl bg-white p-8 shadow-xl ring-1 ring-gray-200">
                        <div class="text-center">
                            <div class="mx-auto h-32 w-32 rounded-2xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center mb-6">
                                <svg class="h-16 w-16 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Upload Your Photo</h4>
                            <p class="text-gray-600 text-sm mb-6">Drag & drop or click to upload an image of the item you're looking for</p>
                            
                            <!-- Simulated Results -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="h-16 w-full bg-gray-200 rounded mb-2"></div>
                                    <p class="text-xs text-gray-600">95% match</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="h-16 w-full bg-gray-200 rounded mb-2"></div>
                                    <p class="text-xs text-gray-600">87% match</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 h-8 w-8 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 opacity-20"></div>
                    <div class="absolute -bottom-4 -left-4 h-12 w-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 opacity-20"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">How it works</h2>
                <p class="mt-4 text-lg leading-8 text-gray-600">Three simple steps to help items find their way back.</p>
            </div>
            <div class="mx-auto mt-12 grid max-w-5xl grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Browse or search</h3>
                    <p class="mt-2 text-gray-600">Filter by type, category, location, and date. Use keywords or AI image search to find matches instantly.</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Create a post</h3>
                    <p class="mt-2 text-gray-600">Share details and photos. The community and our AI tools amplify your reach.</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-pink-50 text-pink-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Verify & reunite</h3>
                    <p class="mt-2 text-gray-600">Use claims and messages to verify ownership and coordinate a safe return.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Popular categories</h2>
            </div>
            @php
                $categories = ['pet','person','vehicle','electronics','documents','jewelry','personal belongings','other'];
                $icons = [
                    'pet' => '🐕',
                    'person' => '👤',
                    'vehicle' => '🚗',
                    'electronics' => '📱',
                    'documents' => '📄',
                    'jewelry' => '💍',
                    'personal belongings' => '🎒',
                    'other' => '📦',
                ];
            @endphp
            <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <div class="group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="text-2xl">{{ $icons[$category] ?? '📦' }}</div>
                        <div class="mt-3 font-medium text-gray-900">{{ ucfirst($category) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Success Stories / Testimonials -->
    <section class="py-16 sm:py-20 bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                <div>
                    <blockquote class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                        <p class="text-lg leading-7 text-gray-700">“The smallest act of helping can create the biggest smile.”</p>
                        <footer class="mt-4 text-sm text-gray-500">— Mohammed Sayed Anwar</footer>
                    </blockquote>
                    <blockquote class="mt-6 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-100">
                        <p class="text-lg leading-7 text-gray-700">“Reuniting someone with what they’ve lost is a reminder that goodness still exists in the world.”</p>
                        <footer class="mt-4 text-sm text-gray-500">— Mohammed Sayed Anwar</footer>
                    </blockquote>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm">
                    <h3 class="text-2xl font-bold text-gray-900">Real success stories</h3>
                    <p class="mt-3 text-gray-600">See how people like you used {{ config('app.name') }} to reunite items with their owners.</p>
                    <a href="{{ route('success-stories.index') }}" class="mt-6 inline-flex items-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Explore Success Stories
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Section -->
    <section class="py-16 sm:py-20 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                <div class="lg:col-span-2">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Latest feedback</h2>
                    @php
                        $latestFeedback = \App\Models\Feedback::where('is_approved', true)->latest()->limit(4)->get();
                    @endphp
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($latestFeedback as $item)
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->name ?? ($item->user->name ?? 'Anonymous') }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!is_null($item->rating))
                                        <div class="text-yellow-500 text-sm" aria-label="Rating: {{ $item->rating }} out of 5">
                                            @for($i=1;$i<=5;$i++)
                                                <span class="{{ $i <= $item->rating ? 'opacity-100' : 'opacity-30' }}">★</span>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-3 text-gray-700">{{ Str::limit($item->message, 220) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No feedback yet.</p>
                        @endforelse
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('feedback.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">Read all feedback →</a>
                    </div>
                </div>
                <div>
                    @include('feedback.partials.form')
                </div>
            </div>
        </div>
    </section>


    <!-- Landing Page Footer (only here) -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="text-xl font-bold text-gray-900">{{ config('app.name') }}</div>
                    <p class="mt-3 text-sm text-gray-600">Reuniting people with their belongings through a helpful community and smart tools.</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wide uppercase">Quick Links</h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Home</a></li>
                        <li><a href="{{ route('posts.index') }}" class="text-gray-600 hover:text-gray-900">Browse Items</a></li>
                        <li><a href="{{ route('success-stories.index') }}" class="text-gray-600 hover:text-gray-900">Success Stories</a></li>
                        <li><a href="{{ route('feedback.index') }}" class="text-gray-600 hover:text-gray-900">Feedback</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wide uppercase">Resources</h3>
                    <ul class="mt-4 space-y-2 text-sm">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a></li>
                            <li><a href="{{ route('posts.create') }}" class="text-gray-600 hover:text-gray-900">Post Item</a></li>
                            <li><a href="{{ route('posts.my-posts') }}" class="text-gray-600 hover:text-gray-900">My Posts</a></li>
                            <li><a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-gray-900">Messages</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wide uppercase">Stay Connected</h3>
                    <p class="mt-4 text-sm text-gray-600">Have a suggestion? Share it on the feedback page.</p>
                    <a href="{{ route('feedback.index') }}" class="mt-3 inline-flex items-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Give Feedback
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved, Developed by Sayed-2147, Sakib-2107, Miraj-2106 | Department of CSE, Premier University Chattogram.
</p>
            </div>
        </div>
    </footer>
</x-app-layout>
