<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 4 users with password "12345678"
        $users = [
            [
                'name' => 'User One',
                'email' => 'user1@example.com',
                'password' => '12345678',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User Two',
                'email' => 'user2@example.com',
                'password' => '12345678',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User Three',
                'email' => 'user3@example.com',
                'password' => '12345678',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User Four',
                'email' => 'user4@example.com',
                'password' => '12345678',
                'email_verified_at' => now(),
            ],
        ];

        $createdUsers = collect($users)->map(fn ($data) => User::create($data));

        // For each user create 4 posts: 2 electronics, 1 pet, 1 bike (no images)
        foreach ($createdUsers as $index => $user) {
            // 2 electronics
            Post::create([
                'user_id' => $user->id,
                'title' => 'Lost Electronic Device #' . ($index + 1) . 'A',
                'description' => 'Lost electronic device. No image provided.',
                'category' => 'electronics',
                'location' => 'Location A',
                'date_lost_found' => now()->subDays(2),
                'type' => 'lost',
                'status' => 'active',
            ]);

            Post::create([
                'user_id' => $user->id,
                'title' => 'Found Electronic Device #' . ($index + 1) . 'B',
                'description' => 'Found electronic device. No image provided.',
                'category' => 'electronics',
                'location' => 'Location B',
                'date_lost_found' => now()->subDays(1),
                'type' => 'found',
                'status' => 'active',
            ]);

            // 1 pet
            Post::create([
                'user_id' => $user->id,
                'title' => 'Missing Pet #' . ($index + 1),
                'description' => 'Pet missing/found. No image provided.',
                'category' => 'pet',
                'location' => 'Neighborhood',
                'date_lost_found' => now()->subDays(3),
                'type' => 'lost',
                'status' => 'active',
            ]);

            // 1 bike
            Post::create([
                'user_id' => $user->id,
                'title' => 'Lost Bike #' . ($index + 1),
                'description' => 'Bike reported lost. No image provided.',
                'category' => 'bike',
                'location' => 'City Park',
                'date_lost_found' => now()->subDays(4),
                'type' => 'lost',
                'status' => 'active',
            ]);
        }
    }
}