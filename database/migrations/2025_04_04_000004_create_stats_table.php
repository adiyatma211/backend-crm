<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('value', 100);
            $table->string('label', 255);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE stats ADD CONSTRAINT chk_stat_value_length CHECK (char_length(value) >= 1)');
        DB::statement('ALTER TABLE stats ADD CONSTRAINT chk_stat_label_length CHECK (char_length(label) >= 2)');

        Schema::table('stats', function (Blueprint $table) {
            $table->index('label');
            $table->index('display_order');
            $table->index('is_active');
            $table->index('created_at');
        });

        DB::statement('CREATE INDEX idx_stats_active_ordered ON stats(is_active, display_order) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
