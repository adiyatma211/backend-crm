<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            CREATE OR REPLACE FUNCTION update_updated_at_column()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = NOW();
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql
        ');

        $tables = ['projects', 'testimonials', 'stats', 'clients', 'process_info'];

        foreach ($tables as $table) {
            DB::statement("
                CREATE TRIGGER update_{$table}_updated_at
                BEFORE UPDATE ON {$table}
                FOR EACH ROW EXECUTE FUNCTION update_updated_at_column()
            ");
        }
    }

    public function down(): void
    {
        $tables = ['projects', 'testimonials', 'stats', 'clients', 'process_info'];

        foreach ($tables as $table) {
            DB::statement("DROP TRIGGER IF EXISTS update_{$table}_updated_at ON {$table}");
        }

        DB::statement('DROP FUNCTION IF EXISTS update_updated_at_column() CASCADE');
    }
};
