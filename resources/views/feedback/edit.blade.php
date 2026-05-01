<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Feedback') }}</h2>
            <a href="{{ route('feedback.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('feedback.update', $feedback) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="rating" value="Rating (1-5, optional)" />
                            <select id="rating" name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">No rating</option>
                                @for($i=5;$i>=1;$i--)
                                    <option value="{{ $i }}" @selected(old('rating', $feedback->rating)==$i)>{{ $i }} Star{{ $i>1?'s':'' }}</option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="message" value="Feedback" />
                            <textarea id="message" name="message" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('message', $feedback->message) }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>
                        <div class="flex items-center gap-2">
                            <x-primary-button type="submit">Save Changes</x-primary-button>
                            <a href="{{ route('feedback.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
