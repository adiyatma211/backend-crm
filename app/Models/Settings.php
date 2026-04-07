<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'site_name',
        'site_description',
        'logo_url',
        'logo_light_url',
        'logo_dark_url',
        'logo_alt_text',
        'enable_dark_mode',
        'enable_compact_mode',
    ];

    protected $casts = [
        'logo_url' => 'string',
        'logo_light_url' => 'string',
        'logo_dark_url' => 'string',
        'enable_dark_mode' => 'boolean',
        'enable_compact_mode' => 'boolean',
    ];

    public function getLogoUrl($darkMode = false)
    {
        return $darkMode && $this->logo_dark_url ? $this->logo_dark_url : $this->logo_url;
    }
}
