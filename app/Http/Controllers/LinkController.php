<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LinkController extends Controller
{
    public function index()
    {
        $links = Link::latest()->take(5)->get();
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

        return redirect()->back()->with('success', 'URL shortened successfully!');
    }

    public function redirect($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();
        $link->increment('visits');

        return redirect($link->original_url);
    }
}
