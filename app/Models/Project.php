<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'slug',
        'title',
        'category',
        'description',
        'status',
        'image',
        'project_url',
        'technologies',
        'features',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'technologies' => 'array',
        'features' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
