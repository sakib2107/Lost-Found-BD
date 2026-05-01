<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'location',
        'date_lost_found',
        'images',
        'embeddings',
        'type',
        'status',
            ];

    protected $casts = [
        'date_lost_found' => 'date',
                'embeddings' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        // When a post is being deleted, clean up related data
        static::deleting(function ($post) {
            // Delete related notifications from the notifications table
            // This handles Laravel's built-in notification system
            DB::table('notifications')
                ->where('data->post_id', $post->id)
                ->delete();

            // Also check for notifications that might reference the post in different ways
            DB::table('notifications')
                ->whereJsonContains('data', ['post_id' => $post->id])
                ->delete();

            // Delete related found notifications
            $post->foundNotifications()->delete();

            // Delete related claims
            $post->claims()->delete();

            // Delete related comments
            $post->comments()->delete();

            // Delete related messages
            $post->messages()->delete();

                    });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function foundNotifications(): HasMany
    {
        return $this->hasMany(FoundNotification::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }
}