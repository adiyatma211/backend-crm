<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('table_name', 100);
            $table->uuid('record_id');
            $table->string('action', 20);
            $table->jsonb('old_values')->nullable();
            $table->jsonb('new_values')->nullable();
            $table->string('changed_by', 255);
            $table->timestamp('changed_at')->default(DB::raw('NOW()'));
        });

        DB::statement('ALTER TABLE audit_logs ADD CONSTRAINT chk_audit_action_valid CHECK (action IN (\'INSERT\', \'UPDATE\', \'DELETE\'))');

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('table_name');
            $table->index('record_id');
            $table->index('action');
            $table->index('changed_by');
            $table->index('changed_at');
        });

        DB::statement('CREATE INDEX idx_audit_logs_old_values ON audit_logs USING GIN(old_values)');
        DB::statement('CREATE INDEX idx_audit_logs_new_values ON audit_logs USING GIN(new_values)');
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
