<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\CustomVerifyEmail;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable implements MustVerifyEmail 
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        Mail::to($this->email)->send(new CustomVerifyEmail($this));
    }

    /**
     * Get the profile image URL or default placeholder
     *
     * @return string
     */
    public function getProfileImageUrl(): string
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        // Return a default placeholder or generate initials-based avatar
        return $this->getDefaultProfileImage();
    }

    /**
     * Get default profile image (initials-based)
     *
     * @return string
     */
    public function getDefaultProfileImage(): string
    {
        // Generate initials from name
        $initials = collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');
        
        // Generate a color based on user ID for consistency
        $colors = [
            'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 
            'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-gray-500'
        ];
        $colorIndex = $this->id % count($colors);
        $bgColor = $colors[$colorIndex];
        
        // Return data URL for SVG avatar
        $svg = '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
            <rect width="100" height="100" fill="' . $this->getColorHex($bgColor) . '"/>
            <text x="50" y="50" font-family="Arial, sans-serif" font-size="36" font-weight="bold" 
                  text-anchor="middle" dominant-baseline="central" fill="white">' . $initials . '</text>
        </svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Convert Tailwind color class to hex
     *
     * @param string $colorClass
     * @return string
     */
    private function getColorHex(string $colorClass): string
    {
        $colorMap = [
            'bg-red-500' => '#ef4444',
            'bg-blue-500' => '#3b82f6',
            'bg-green-500' => '#10b981',
            'bg-yellow-500' => '#f59e0b',
            'bg-purple-500' => '#8b5cf6',
            'bg-pink-500' => '#ec4899',
            'bg-indigo-500' => '#6366f1',
            'bg-gray-500' => '#6b7280'
        ];
        
        return $colorMap[$colorClass] ?? '#6b7280';
    }
}
