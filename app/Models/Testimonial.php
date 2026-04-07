<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'text',
        'author',
        'title',
        'initials',
        'rating',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
