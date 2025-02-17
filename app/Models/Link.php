<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = ['original_url', 'short_code', 'visits'];

    protected $appends = ['short_url'];

    public function visits()
    {
        return $this->hasMany(LinkVisit::class);
    }

    public function getShortUrlAttribute()
    {
        return url($this->short_code);
    }

    // Add methods for statistics
    public function getDailyVisits()
    {
        return $this->visits()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getBrowserStats()
    {
        return $this->visits()
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get();
    }

    public function getCountryStats()
    {
        return $this->visits()
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->get();
    }

    public function getReferrerStats()
    {
        return $this->visits()
            ->selectRaw('referrer, COUNT(*) as count')
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->get();
    }
}
