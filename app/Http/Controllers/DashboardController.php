<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get total links and total clicks in one query
        $basicStats = Link::where('user_id', $user->id)
            ->select([
                DB::raw('COUNT(*) as total_links'),
                DB::raw('SUM(visits) as total_clicks'),
                DB::raw('COUNT(CASE
                    WHEN expires_at IS NULL OR expires_at > NOW()
                    THEN 1
                END) as active_links')
            ])
            ->first();

        // Get recent activity stats
        $recentStats = Link::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as links_created'),
                DB::raw('SUM(visits) as clicks')
            ])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $stats = [
            'totalLinks' => $basicStats->total_links ?? 0,
            'totalClicks' => $basicStats->total_clicks ?? 0,
            'activeLinks' => $basicStats->active_links ?? 0,
            'recentActivity' => [
                'dates' => $recentStats->pluck('date'),
                'linksCreated' => $recentStats->pluck('links_created'),
                'clicks' => $recentStats->pluck('clicks'),
            ],
            'conversionRate' => $basicStats->total_links > 0
                ? round(($basicStats->total_clicks / $basicStats->total_links), 2)
                : 0,
        ];

        // Get recent links with visit counts
        $recentLinks = Link::where('user_id', $user->id)
            ->withCount(['visits as recent_visits' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(7));
            }])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($link) => [
                'id' => $link->id,
                'original_url' => $link->original_url,
                'short_url' => $link->short_url,
                'short_code' => $link->short_code,
                'visits' => $link->visits,
                'recent_visits' => $link->recent_visits,
                'created_at' => $link->created_at->format('Y-m-d H:i:s'),
                'expires_at' => $link->expires_at
                    ? $link->expires_at->format('Y-m-d H:i:s')
                    : null,
                'status' => $this->getLinkStatus($link),
            ]);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentLinks' => $recentLinks,
        ]);
    }

    private function getLinkStatus($link)
    {
        if ($link->expires_at && $link->expires_at < now()) {
            return 'expired';
        }
        if ($link->max_visits && $link->visits >= $link->max_visits) {
            return 'limit-reached';
        }
        if ($link->password) {
            return 'protected';
        }
        return 'active';
    }
}
