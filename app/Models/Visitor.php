<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'ip_address',
        'user_agent',
        'session_id',
        'page_url',
        'referrer',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * Relationship dengan Domain
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Scope untuk mendapatkan unique visitors
     */
    public function scopeUniqueVisitors($query)
    {
        return $query->distinct('session_id');
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('visited_at', [$startDate, $endDate]);
    }

    /**
     * Scope untuk hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', today());
    }

    /**
     * Scope untuk bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month)
                     ->whereYear('visited_at', now()->year);
    }
}
