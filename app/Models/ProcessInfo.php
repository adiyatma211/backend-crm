<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessInfo extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'label',
        'value',
        'unit',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:0',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
