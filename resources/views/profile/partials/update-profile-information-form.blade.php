<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and profile picture.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Image Section -->
        <div class="space-y-4">
            <x-input-label for="profile_image" :value="__('Profile Picture')" />
            
            <!-- Current Profile Image Display -->
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <img id="profile-preview" class="h-20 w-20 object-cover rounded-full border-2 border-gray-300" 
                         src="{{ $user->getProfileImageUrl() }}" alt="Current profile photo">
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-4">
                        <!-- File Input -->
                        <label for="profile_image" class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span>Change Photo</span>
                            <input id="profile_image" name="profile_image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                        </label>
                        
                        <!-- Remove Button (only show if user has a profile image) -->
                        @if($user->profile_image)
                            <button type="button" onclick="removeProfileImage()" class="bg-red-100 py-2 px-3 border border-red-300 rounded-md shadow-sm text-sm leading-4 font-medium text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Remove
                            </button>
                        @endif
                    </div>
                    <p class="mt-2 text-xs text-gray-500">JPG, PNG, GIF up to 2MB</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-100" :value="old('email', $user->email)" required autocomplete="username" readonly />
            <p class="mt-2 text-xs text-gray-500">Email changes are disabled.</p>
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        }

        function removeProfileImage() {
            // Use the existing confirmation system
            confirmationSystem.showConfirmation({
                title: 'Remove Profile Picture',
                message: 'Are you sure you want to remove your profile picture?',
                confirmText: 'Remove',
                confirmClass: 'bg-red-500 hover:bg-red-700',
                icon: 'warning',
                onConfirm: () => {
                    // Create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("profile.remove-image") }}';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</section>