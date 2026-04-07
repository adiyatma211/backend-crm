<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('slug', 255)->unique();
            $table->string('title', 255);
            $table->string('category', 100);
            $table->text('description');
            $table->string('status', 50);
            $table->string('image', 500);
            $table->string('project_url', 500);
            $table->jsonb('technologies')->default('[]');
            $table->jsonb('features')->default('[]');
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE projects ADD CONSTRAINT chk_project_slug_length CHECK (char_length(slug) >= 3)');
        DB::statement('ALTER TABLE projects ADD CONSTRAINT chk_project_title_length CHECK (char_length(title) >= 2)');
        DB::statement('ALTER TABLE projects ADD CONSTRAINT chk_project_technologies_type CHECK (jsonb_typeof(technologies) = \'array\')');
        DB::statement('ALTER TABLE projects ADD CONSTRAINT chk_project_features_type CHECK (jsonb_typeof(features) = \'array\')');

        Schema::table('projects', function (Blueprint $table) {
            $table->index('slug');
            $table->index('category');
            $table->index('status');
            $table->index('display_order');
            $table->index('is_active');
            $table->index('created_at');
        });

        DB::statement('CREATE INDEX idx_projects_active_ordered ON projects(is_active, display_order) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_projects_technologies ON projects USING GIN(technologies)');
        DB::statement('CREATE INDEX idx_projects_features ON projects USING GIN(features)');
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
