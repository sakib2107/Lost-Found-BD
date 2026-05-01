@props([
    'user',
    'size' => 'md',
    'class' => ''
])

@php
    $sizeClasses = [
        'xs' => 'h-6 w-6',
        'sm' => 'h-8 w-8',
        'md' => 'h-10 w-10',
        'lg' => 'h-12 w-12',
        'xl' => 'h-16 w-16',
        '2xl' => 'h-20 w-20',
        '3xl' => 'h-24 w-24'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="inline-block {{ $sizeClass }} {{ $class }}">
    @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
        <img class="{{ $sizeClass }} rounded-full object-cover border-2 border-gray-200" 
             src="{{ Storage::url($user->profile_image) }}" 
             alt="{{ $user->name }}'s profile picture">
    @else
        <!-- Default avatar with initials -->
        @php
            $initials = collect(explode(' ', $user->name))
                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                ->take(2)
                ->implode('');
            
            $colors = [
                'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 
                'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-gray-500'
            ];
            $colorIndex = $user->id % count($colors);
            $bgColor = $colors[$colorIndex];
        @endphp
        
        <div class="{{ $sizeClass }} {{ $bgColor }} rounded-full flex items-center justify-center border-2 border-gray-200">
            <span class="text-white font-semibold text-{{ $size === 'xs' ? 'xs' : ($size === 'sm' ? 'sm' : ($size === 'md' ? 'base' : ($size === 'lg' ? 'lg' : ($size === 'xl' ? 'xl' : ($size === '2xl' ? '2xl' : '3xl'))))) }}">
                {{ $initials }}
            </span>
        </div>
    @endif
</div>