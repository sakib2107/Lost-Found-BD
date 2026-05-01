<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clean up any temp search images for this session before logout
        $this->cleanupTempSearchImages($request);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Clean up temporary search images for the current session
     */
    private function cleanupTempSearchImages(Request $request)
    {
        try {
            $sessionId = $request->session()->getId();
            $disk = Storage::disk('public');
            
            // Get current search image from session and delete it
            $currentSearchImage = $request->session()->get('current_search_image');
            if ($currentSearchImage && $disk->exists($currentSearchImage)) {
                $disk->delete($currentSearchImage);
                Log::info('Cleaned up search image on logout', ['file' => $currentSearchImage]);
            }

            // Also clean up any orphaned files for this session
            $tempSearchPath = 'temp/search';
            if ($disk->exists($tempSearchPath)) {
                $files = $disk->files($tempSearchPath);
                foreach ($files as $file) {
                    $filename = basename($file);
                    // Check if file belongs to current session
                    if (Str::contains($filename, 'search_' . $sessionId . '_')) {
                        $disk->delete($file);
                        Log::info('Cleaned up orphaned search image on logout', ['file' => $file]);
                    }
                }
            }

        } catch (\Exception $e) {
            Log::warning('Error during logout search image cleanup', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
