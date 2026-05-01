<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Share your feedback</h3>
        <p class="text-sm text-gray-600 mb-4">Tell us what you think about {{ config('app.name') }}.</p>

        {{-- Success messages now handled by toast system in layout --}}

        @auth
            <form method="POST" action="{{ route('feedback.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="rating" value="Rating (1-5, optional)" />
                    <select id="rating" name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">No rating</option>
                        @for($i=5;$i>=1;$i--)
                            <option value="{{ $i }}" @selected(old('rating')==$i)>{{ $i }} Star{{ $i>1?'s':'' }}</option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="message" value="Feedback" />
                    <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('message') }}</textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                </div>
                <x-primary-button type="submit">Submit Feedback</x-primary-button>
            </form>
        @else
            <div class="p-6 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Join the conversation</h4>
                    <p class="text-gray-600 mb-6">Sign in to share your feedback and help us improve the platform.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg shadow-sm hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Join Now
                        </a>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</div>
