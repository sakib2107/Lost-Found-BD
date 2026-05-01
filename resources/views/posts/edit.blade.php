<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Side - Image Upload -->
                            <div class="space-y-6">
                                <div class="text-center">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Images</h3>
                                    
                                    <!-- Current Image Display -->
                                    @if($post->images)
                                        <div id="current-image" class="mb-4">
                                            <img src="{{ url('storage/' . $post->images) }}" 
                                                 alt="{{ $post->title }}" 
                                                 class="w-full h-64 object-contain bg-gray-50 rounded-lg border-2 border-gray-300"
                                                 onerror="this.parentElement.style.display='none';">
                                            <p class="mt-2 text-sm text-gray-500">Current image</p>
                                        </div>
                                    @endif
                                    
                                    <!-- New Image Preview Area -->
                                    <div id="image-preview" class="mb-4 hidden relative">
                                        <img id="preview-img" src="" alt="Preview" class="w-full h-64 object-contain bg-gray-50 rounded-lg border-2 border-gray-300">
                                        <p class="mt-2 text-sm text-gray-500">New image preview</p>
                                        <button type="button" id="remove-image" class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Upload Area -->
                                    <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-lg p-8 hover:border-gray-400 transition-colors">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="mt-4">
                                                <label for="images" class="cursor-pointer">
                                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                                        {{ $post->images ? 'Click to replace image' : 'Click to upload image' }}
                                                    </span>
                                                    <span class="mt-1 block text-sm text-gray-500">
                                                        PNG, JPG, GIF up to 2MB
                                                    </span>
                                                </label>
                                                <input type="file" name="images" id="images" accept="image/*" class="sr-only">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('images')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Upload Tips -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-blue-900 mb-2">📸 Photo Tips</h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• Use clear, well-lit photos</li>
                                        <li>• Show distinctive features</li>
                                        <li>• Include multiple angles if possible</li>
                                        <li>• Avoid blurry or dark images</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Right Side - Form Fields -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Item Details</h3>
                                
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Type and Category Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Type -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="type" value="lost" {{ old('type', $post->type) === 'lost' ? 'checked' : '' }} required
                                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                                <span class="ml-3 text-sm text-gray-700">🔍 Lost Item</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                                <input type="radio" name="type" value="found" {{ old('type', $post->type) === 'found' ? 'checked' : '' }} required
                                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                                <span class="ml-3 text-sm text-gray-700">✅ Found Item</span>
                                            </label>
                                        </div>
                                        @error('type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Category -->
                                    <div>
                                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                        <select name="category" id="category" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('category') border-red-500 @enderror">
                                            <option value="">Select category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category }}" {{ old('category', $post->category) === $category ? 'selected' : '' }}>
                                                    {{ ucfirst($category) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                    <textarea name="description" id="description" rows="4" required
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $post->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Location and Date Row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Location -->
                                    <div>
                                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                                        <input type="text" name="location" id="location" value="{{ old('location', $post->location) }}" required
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('location') border-red-500 @enderror">
                                        @error('location')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Date Lost/Found -->
                                    <div>
                                        <label for="date_lost_found" class="block text-sm font-medium text-gray-700 mb-2">Date Lost/Found *</label>
                                        <input type="date" name="date_lost_found" id="date_lost_found" value="{{ old('date_lost_found', $post->date_lost_found->format('Y-m-d')) }}" required
                                               max="{{ date('Y-m-d') }}"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('date_lost_found') border-red-500 @enderror">
                                        @error('date_lost_found')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                    <select name="status" id="status" required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                                        <option value="active" {{ old('status', $post->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="resolved" {{ old('status', $post->status) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex justify-between pt-6 border-t border-gray-200">
                                    <a href="{{ route('posts.show', $post) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                            class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Update Post
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Image Preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('images');
            const uploadArea = document.getElementById('upload-area');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const removeBtn = document.getElementById('remove-image');
            const currentImage = document.getElementById('current-image');

            // Handle file input change
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        if (currentImage) {
                            currentImage.style.opacity = '0.5';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle remove image
            removeBtn.addEventListener('click', function() {
                imageInput.value = '';
                previewImg.src = '';
                imagePreview.classList.add('hidden');
                if (currentImage) {
                    currentImage.style.opacity = '1';
                }
            });

            // Handle drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-indigo-500', 'bg-indigo-50');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    imageInput.files = files;
                    const event = new Event('change', { bubbles: true });
                    imageInput.dispatchEvent(event);
                }
            });
        });
    </script>
</x-app-layout>