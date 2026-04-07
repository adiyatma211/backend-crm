<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Sentosa CMS');
            $table->text('site_description')->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('logo_light_url', 500)->nullable();
            $table->string('logo_dark_url', 500)->nullable();
            $table->string('logo_alt_text', 255)->nullable();
            $table->boolean('enable_dark_mode')->default(false);
            $table->boolean('enable_compact_mode')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
