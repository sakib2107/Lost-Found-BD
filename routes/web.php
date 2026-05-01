<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoundNotificationController;
use App\Http\Controllers\AIImageSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;

// Public routes
Route::view('/', 'landing')->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->whereNumber('post')->name('posts.show');
Route::get('/success-stories', [App\Http\Controllers\SuccessStoryController::class, 'index'])->name('success-stories.index');
Route::get('/success-stories/{post}', [App\Http\Controllers\SuccessStoryController::class, 'show'])->name('success-stories.show');

// Feedback routes
Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
Route::post('/feedback', [FeedbackController::class, 'store'])->middleware(['auth'])->name('feedback.store');
Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->middleware(['auth'])->name('feedback.edit');
Route::patch('/feedback/{feedback}', [FeedbackController::class, 'update'])->middleware(['auth'])->name('feedback.update');
Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->middleware(['auth'])->name('feedback.destroy');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/remove-image', [ProfileController::class, 'removeProfileImage'])->name('profile.remove-image');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Post routes
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/posts/{post}/status', [PostController::class, 'updateStatus'])->name('posts.update-status');
    Route::get('/posts/{post}/claims-and-found', [PostController::class, 'showClaimsAndFound'])->name('posts.claims-and-found');
    
    // My Posts Management
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my-posts');
    Route::delete('/my-posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulk-delete');

    // Claim routes
    Route::post('/posts/{post}/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::patch('/claims/{claim}/accept', [ClaimController::class, 'accept'])->name('claims.accept');
    Route::patch('/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
    Route::get('/my-claims', [ClaimController::class, 'index'])->name('claims.index');
    
    // Found notification routes
    Route::post('/posts/{post}/found-notifications', [FoundNotificationController::class, 'store'])->name('found-notifications.store');
    Route::patch('/found-notifications/{foundNotification}/accept', [FoundNotificationController::class, 'accept'])->name('found-notifications.accept');
    Route::patch('/found-notifications/{foundNotification}/reject', [FoundNotificationController::class, 'reject'])->name('found-notifications.reject');
    Route::get('/my-found-notifications', [FoundNotificationController::class, 'index'])->name('found-notifications.index');
    Route::get('/received-found-notifications', [FoundNotificationController::class, 'received'])->name('found-notifications.received');

    // Comment routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Message routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{post}/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{post}/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{post}/{user}/clear', [MessageController::class, 'clearConversation'])->name('messages.clear-conversation');
    Route::get('/posts/{post}/message', [MessageController::class, 'create'])->name('messages.create');

    
    
    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/clear-all', [App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // AI Image Search routes
    Route::get('/ai-search', [AIImageSearchController::class, 'index'])->name('ai-search.index');
    Route::post('/ai-search', [AIImageSearchController::class, 'search'])->name('ai-search.search');
});

// Unread count route available to any authenticated user (no email verification required)
Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])
    ->middleware(['auth'])
    ->name('notifications.unread-count');

require __DIR__.'/auth.php';
