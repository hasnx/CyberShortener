<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\Agent;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LinkController extends Controller
{
    public function index()
    {
        $links = Link::active()  // This already filters expired links
            ->when(Auth::check(), function ($query) {
                // For authenticated users: show their own links and some recent public links
                return $query->where(function ($q) {
                    $q->where('user_id', Auth::id())
                        ->orWhereNull('user_id');
                });
            }, function ($query) {
                // For guests: show only links without a user (anonymous links)
                return $query->whereNull('user_id');
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'original_url' => $link->original_url,
                    'short_url' => $link->short_url,
                    'short_code' => $link->short_code,
                    'visits' => $link->visits,
                    'expires_at' => $link->expires_at,
                    'is_custom' => $link->is_custom,
                    'created_at' => $link->created_at,
                    'user_id' => $link->user_id
                ];
            });

        // Add this line temporarily to debug
        // dd($links->toArray());

        return Inertia::render('Links/Index', [
            'links' => $links,
            'flash' => [
                'success' => session('success'),
            ],
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : (object)[],
        ]);
    }

    public function userLinks()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $links = Link::forUser(Auth::id())
            ->latest()
            ->paginate(10)
            ->through(function ($link) {
                return [
                    'id' => $link->id,
                    'original_url' => $link->original_url,
                    'short_url' => $link->short_url,
                    'short_code' => $link->short_code,
                    'visits' => $link->visits,
                    'expires_at' => $link->expires_at,
                    'is_custom' => $link->is_custom,
                    'created_at' => $link->created_at,
                    'user_id' => $link->user_id
                ];
            });

        return Inertia::render('Links/UserLinks', [
            'links' => $links
        ]);
    }

    public function store(Request $request)
    {
        // Check if guest user tries to use advanced features
        if (!Auth::check() && ($request->has('short_code') || $request->has('expires_at'))) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'url' => 'required|url|max:2048',
            'short_code' => [
                'nullable',
                'min:4',
                'max:20',
                'regex:/^[a-zA-Z0-9-_]+$/',
                Rule::unique('links', 'short_code'),
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],
            'password' => 'nullable|string|min:4',  // Add password validation
            'max_clicks' => 'nullable|integer|min:1',
            'qr_style' => 'nullable|string',
            'qr_options' => 'nullable|array',
        ], [
            'short_code.regex' => 'Custom code can only contain letters, numbers, hyphens, and underscores',
            'short_code.unique' => 'This custom code is already taken',
            'expires_at.after' => 'Expiration date must be in the future',
        ]);

        $link = Link::create([
            'original_url' => $validated['url'],
            'short_code' => $validated['short_code'] ?? Str::random(6),
            'user_id' => Auth::check() ? Auth::id() : null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_custom' => isset($validated['short_code']),
            'visits' => 0,
            'password' => $validated['password'] ? Hash::make($validated['password']) : null,
            'max_clicks' => $validated['max_clicks'] ?? null,
            'qr_style' => $validated['qr_style'] ?? 'classic',
            'qr_options' => $validated['qr_options'] ?? null,
        ]);

        // Add this line for debugging
        // dd($link->toArray());

        return redirect()->back()->with('success', 'URL shortened successfully!');
    }

    public function redirect($shortCode)
    {
        $link = Link::where('short_code', $shortCode)
            ->active()
            ->firstOrFail();

        // Check if link has reached click limit
        if ($link->hasReachedClickLimit()) {
            abort(403, 'This link has reached its maximum click limit.');
        }

        // Check if password protected and not verified
        if ($link->password && !session("link_password_{$link->id}")) {
            return Inertia::render('Links/PasswordCheck', [
                'shortCode' => $shortCode,
                'csrf' => csrf_token(),
            ]);
        }

        // Track visit and redirect
        $this->trackVisit($link);
        return redirect($link->original_url);
    }

    public function verifyPassword(Request $request, Link $link)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        if (!$link->verifyPassword($request->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password'
            ]);
        }

        // Store password in session for this link
        session(["link_password_{$link->id}" => $request->password]);

        // Redirect to the original URL
        $url = session('intended_url');
        session()->forget('intended_url');

        return redirect($url);
    }

    protected function trackVisit(Link $link)
    {
        $agent = new Agent();

        // Get location data from IP (using ip-api.com as an example)
        $ipData = Http::get("http://ip-api.com/json/" . request()->ip())->json();

        $link->visits()->create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('referer'),
            'country' => $ipData['status'] === 'success' ? $ipData['country'] : null,
            'city' => $ipData['status'] === 'success' ? $ipData['city'] : null,
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device' => $agent->isDesktop() ? 'Desktop' : ($agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Unknown')),
        ]);

        $link->increment('visits');
    }

    public function stats($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();

        return Inertia::render('Links/Stats', [
            'link' => [
                'id' => $link->id,
                'original_url' => $link->original_url,
                'short_url' => $link->short_url,
                'visits' => $link->visits,
                'daily_visits' => $link->getDailyVisits(),
                'browser_stats' => $link->getBrowserStats(),
                'country_stats' => $link->getCountryStats(),
                'referrer_stats' => $link->getReferrerStats(),
            ]
        ]);
    }

    public function destroy(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            abort(403);
        }

        $link->delete();
        return redirect()->back()->with('success', 'Link deleted successfully');
    }

    public function checkPassword(Request $request, $shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();

        if (!$link->password) {
            return redirect($link->original_url);
        }

        $request->validate([
            'password' => 'required|string'
        ]);

        if (!Hash::check($request->password, $link->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password'
            ]);
        }

        // Store in session that this password has been verified
        session(["link_password_{$link->id}" => true]);

        // Track visit before redirecting
        $this->trackVisit($link);

        // Use regular redirect instead of Inertia response
        return redirect()->away($link->original_url);
    }
}
