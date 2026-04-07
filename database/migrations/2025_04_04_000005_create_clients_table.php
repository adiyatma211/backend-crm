<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('name', 255)->unique();
            $table->string('initial', 10);
            $table->string('logo_url', 500)->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE clients ADD CONSTRAINT chk_client_name_length CHECK (char_length(name) >= 2)');
        DB::statement('ALTER TABLE clients ADD CONSTRAINT chk_client_initial_length CHECK (char_length(initial) >= 2 AND char_length(initial) <= 10)');

        Schema::table('clients', function (Blueprint $table) {
            $table->index('name');
            $table->index('initial');
            $table->index('display_order');
            $table->index('is_active');
            $table->index('created_at');
        });

        DB::statement('CREATE INDEX idx_clients_active_ordered ON clients(is_active, display_order) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
