<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'type',
        'properties',
        'digital_product_path',
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
