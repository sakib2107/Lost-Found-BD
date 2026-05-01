<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Community Feedback') }}</h2>
            <a href="{{ route('home') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to Home</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filters: Modern Toolbar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <form method="GET" action="{{ route('feedback.index') }}" class="flex flex-col gap-3">
                        <div class="flex flex-col lg:flex-row items-stretch gap-3">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search feedback, names, emails..."
                                           class="w-full rounded-full border border-gray-300 pl-10 pr-4 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <select name="rating" class="rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All ratings</option>
                                    @for($i=5;$i>=1;$i--)
                                        <option value="{{ $i }}" @selected(request('rating')==$i)>{{ $i }} ★</option>
                                    @endfor
                                    <option value="no_rating" @selected(request('rating')==='no_rating')>No rating</option>
                                </select>
                                <select name="sort" class="rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="newest" @selected(request('sort')==='newest')>Newest</option>
                                    <option value="oldest" @selected(request('sort')==='oldest')>Oldest</option>
                                    <option value="rating_highest" @selected(request('sort')==='rating_highest')>Rating: High → Low</option>
                                    <option value="rating_lowest" @selected(request('sort')==='rating_lowest')>Rating: Low → High</option>
                                </select>
                                <button type="submit" class="inline-flex items-center rounded-full bg-indigo-600 px-4 py-2 text-white font-medium shadow hover:bg-indigo-700">Apply</button>
                                <a href="{{ route('feedback.index') }}" class="inline-flex items-center rounded-full bg-gray-100 px-4 py-2 text-gray-700 font-medium shadow hover:bg-gray-200">Reset</a>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="text-sm text-gray-600">From
                                <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </label>
                            <label class="text-sm text-gray-600">To
                                <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </label>
                        </div>
                        @if(request()->hasAny(['search','rating','date_from','date_to','sort']))
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm text-gray-600">Active:</span>
                                @if(request('search'))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">Search: {{ request('search') }}</span>
                                @endif
                                @if(request('rating'))
                                    <a href="{{ request()->fullUrlWithQuery(['rating'=>null]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">
                                        Rating: {{ request('rating') === 'no_rating' ? 'No rating' : request('rating') . ' ★' }} ×
                                    </a>
                                @endif
                                @if(request('date_from') || request('date_to'))
                                    <a href="{{ request()->fullUrlWithQuery(['date_from'=>null,'date_to'=>null]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Date Range ×</a>
                                @endif
                                @if(request('sort') && request('sort')!=='newest')
                                    <a href="{{ request()->fullUrlWithQuery(['sort'=>null]) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Sort: {{ str_replace('_',' ',request('sort')) }} ×</a>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    @forelse($feedback as $item)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->name ?? ($item->user->name ?? 'Anonymous') }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if(!is_null($item->rating))
                                            <div class="text-yellow-500" aria-label="Rating: {{ $item->rating }} out of 5">
                                                @for($i=1;$i<=5;$i++)
                                                    <span class="{{ $i <= $item->rating ? 'opacity-100' : 'opacity-30' }}">★</span>
                                                @endfor
                                            </div>
                                        @endif
                                        @auth
                                            @if($item->user_id === auth()->id())
                                                <a href="{{ route('feedback.edit', $item) }}"
                                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('feedback.destroy', $item) }}" onsubmit="return false;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDeleteComment(this.form)"
                                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md bg-red-600 text-white hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <p class="mt-3 text-gray-700">{{ $item->message }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-center text-gray-500">No feedback yet.</div>
                        </div>
                    @endforelse

                    <div>
                        {{ $feedback->withQueryString()->links() }}
                    </div>
                </div>
                <div>
                    @include('feedback.partials.form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
