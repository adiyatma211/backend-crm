<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_info', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('label', 255);
            $table->decimal('value', 10, 2);
            $table->string('unit', 50);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE process_info ADD CONSTRAINT chk_process_info_label_length CHECK (char_length(label) >= 2)');
        DB::statement('ALTER TABLE process_info ADD CONSTRAINT chk_process_info_value_positive CHECK (value >= 0)');
        DB::statement('ALTER TABLE process_info ADD CONSTRAINT chk_process_info_unit_length CHECK (char_length(unit) >= 1)');

        Schema::table('process_info', function (Blueprint $table) {
            $table->index('label');
            $table->index('display_order');
            $table->index('is_active');
            $table->index('created_at');
        });

        DB::statement('CREATE INDEX idx_process_info_active_ordered ON process_info(is_active, display_order) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('process_info');
    }
};
