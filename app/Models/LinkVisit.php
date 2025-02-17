<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'city',
        'browser',
        'platform',
        'device',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
