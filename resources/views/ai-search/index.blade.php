<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Image Search') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">AI Image Search</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Upload an image to find visually similar lost and found items using AI technology
                    </p>
                </div>

                <!-- Main Search Form -->
                <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                    <form action="{{ route('ai-search.search') }}" method="POST" enctype="multipart/form-data" id="searchForm">
                        @csrf
                        
                        <!-- Image Upload Section -->
                        <div class="mb-8">
                            <label class="block text-lg font-semibold text-gray-900 mb-4">
                                Upload Your Image
                            </label>
                            
                            <!-- Upload Area with Preview -->
                            <div id="uploadArea" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors cursor-pointer">
                                <!-- Default Upload State -->
                                <div id="uploadPrompt">
                                    <div class="mx-auto w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-medium text-gray-700 mb-2">
                                        <span class="text-blue-600">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>

                                <!-- Image Preview State -->
                                <div id="imagePreview" class="hidden">
                                    <div class="relative inline-block">
                                        <img id="previewImg" src="" alt="Preview" class="max-w-full max-h-64 rounded-lg shadow-md">
                                        <button type="button" id="removeImage" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div> 
                                </div>

                                <input id="search_image" name="search_image" type="file" class="sr-only" accept="image/*" required>
                            </div>

                            @error('search_image')
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <!-- Advanced Options -->
                        <div class="mb-8">
                            <button type="button" id="toggleAdvanced" class="flex items-center text-lg font-semibold text-blue-600 hover:text-blue-700 transition-colors focus:outline-none">
                                <span id="toggleText">Advanced Options</span>
                                <svg id="toggleIcon" class="ml-2 w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div id="advancedOptions" class="hidden mt-6 p-6 bg-gray-50 rounded-lg border">
                                <!-- Similarity Threshold -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        Similarity Threshold: <span id="thresholdValue" class="text-blue-600 font-bold">80%</span>
                                    </label>
                                    <input type="range" id="threshold" name="threshold" min="0.1" max="1" step="0.01" value="0.8" 
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                                        <span>Less Similar (10%)</span>
                                        <span>More Similar (100%)</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">Higher values return more precise matches</p>
                                </div>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="text-center">
                            <button type="submit" id="searchBtn" class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg id="searchIcon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span id="searchBtnText">Search Similar Images</span>
                                <svg id="searchSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- How it Works -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">How AI Image Search Works</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Upload Image</h3>
                            <p class="text-gray-600">Upload a clear photo of the item you're looking for</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">2. AI Analysis</h3>
                            <p class="text-gray-600">Our AI analyzes visual features and patterns</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Find Matches</h3>
                            <p class="text-gray-600">Get ranked results based on visual similarity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        input[type="range"]::-webkit-slider-thumb {
            appearance: none;
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #3B82F6;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        input[type="range"]::-moz-range-thumb {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #3B82F6;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('AI Search page loaded');
            
            // Get all elements
            const fileInput = document.getElementById('search_image');
            const uploadArea = document.getElementById('uploadArea');
            const uploadPrompt = document.getElementById('uploadPrompt');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const removeImageBtn = document.getElementById('removeImage');
            const toggleAdvanced = document.getElementById('toggleAdvanced');
            const toggleText = document.getElementById('toggleText');
            const toggleIcon = document.getElementById('toggleIcon');
            const advancedOptions = document.getElementById('advancedOptions');
            const thresholdSlider = document.getElementById('threshold');
            const thresholdValue = document.getElementById('thresholdValue');
            const searchForm = document.getElementById('searchForm');
            const searchBtn = document.getElementById('searchBtn');
            const searchBtnText = document.getElementById('searchBtnText');
            const searchSpinner = document.getElementById('searchSpinner');
            const searchIcon = document.getElementById('searchIcon');

            // File input change handler
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    console.log('File input changed');
                    const file = e.target.files[0];
                    if (file) {
                        console.log('File selected:', file.name);
                        
                        // Validate file size (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File size must be less than 2MB');
                            fileInput.value = '';
                            return;
                        }
                        
                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            alert('Please select a valid image file (PNG, JPG, GIF)');
                            fileInput.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log('File loaded, showing preview');
                            previewImg.src = e.target.result;
                            uploadPrompt.classList.add('hidden');
                            imagePreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Remove image handler
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Remove image clicked');
                    fileInput.value = '';
                    imagePreview.classList.add('hidden');
                    uploadPrompt.classList.remove('hidden');
                    previewImg.src = '';
                });
            }

            // Upload area click handler
            if (uploadArea) {
                uploadArea.addEventListener('click', function(e) {
                    // Don't trigger file input if clicking on remove button
                    if (e.target.closest('#removeImage')) {
                        return;
                    }
                    fileInput.click();
                });
            }

            // Toggle advanced options
            if (toggleAdvanced && advancedOptions) {
                toggleAdvanced.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Toggle advanced clicked');
                    const isHidden = advancedOptions.classList.contains('hidden');
                    
                    if (isHidden) {
                        advancedOptions.classList.remove('hidden');
                        toggleText.textContent = 'Hide Advanced Options';
                        toggleIcon.style.transform = 'rotate(180deg)';
                    } else {
                        advancedOptions.classList.add('hidden');
                        toggleText.textContent = 'Advanced Options';
                        toggleIcon.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // Threshold slider handler
            if (thresholdSlider && thresholdValue) {
                thresholdSlider.addEventListener('input', function() {
                    const percentage = Math.round(this.value * 100);
                    thresholdValue.textContent = percentage + '%';
                    console.log('Threshold changed to:', percentage + '%');
                });
            }

            // Form submission handler
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    console.log('Form submitted');
                    
                    // Check if file is selected
                    if (!fileInput.files || fileInput.files.length === 0) {
                        e.preventDefault();
                        alert('Please select an image to search');
                        return;
                    }
                    
                    // Update button state
                    if (searchBtn) searchBtn.disabled = true;
                    if (searchBtnText) searchBtnText.textContent = 'Searching...';
                    if (searchSpinner) searchSpinner.classList.remove('hidden');
                    if (searchIcon) searchIcon.classList.add('hidden');
                });
            }

            // Drag and drop functionality
            if (uploadArea) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    uploadArea.classList.add('border-blue-400', 'bg-blue-50');
                }

                function unhighlight(e) {
                    uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
                }

                uploadArea.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    console.log('File dropped');
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files.length > 0) {
                        fileInput.files = files;
                        const event = new Event('change', { bubbles: true });
                        fileInput.dispatchEvent(event);
                    }
                }
            }
        });
    </script>
</x-app-layout>