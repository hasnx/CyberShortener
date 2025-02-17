<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Http;

class LinkController extends Controller
{
    public function index()
    {
        $links = Link::latest()->take(5)->get();

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url|max:2048'
        ]);

        $link = Link::create([
            'original_url' => $validated['url'],
            'short_code' => Str::random(6),
            'visits' => 0
        ]);

        // Add this line for debugging
        // dd($link->toArray());

        return redirect()->back()->with('success', 'URL shortened successfully!');
    }

    public function redirect($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();

        // Track visit
        $this->trackVisit($link);

        return redirect($link->original_url);
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
            'device' => $agent->device(),
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
}
