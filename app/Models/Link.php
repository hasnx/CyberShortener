<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'short_code',
        'visits',
        'user_id',
        'expires_at',
        'is_custom',
        'password',
        'qr_style',
        'max_clicks',
        'qr_options'
    ];

    protected $appends = ['short_url'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_custom' => 'boolean',
        'qr_options' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add scope for active (non-expired) links
    public function scopeActive(Builder $query)
    {
        return $query->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    // Add scope for user's links
    public function scopeForUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

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

    public function isAccessible()
    {
        // Check if link is accessible based on various conditions
        return $this->isActive() && !$this->hasReachedClickLimit();
    }

    public function hasReachedClickLimit()
    {
        return $this->max_clicks !== null && $this->visits >= $this->max_clicks;
    }

    public function verifyPassword($password)
    {
        return $this->password === null || Hash::check($password, $this->password);
    }
}
