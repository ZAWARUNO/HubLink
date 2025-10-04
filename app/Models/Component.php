<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'domain_id',
        'type',
        'properties',
        'order',
        'is_published'
    ];

    protected $casts = [
        'properties' => 'array',
        'is_published' => 'boolean'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}