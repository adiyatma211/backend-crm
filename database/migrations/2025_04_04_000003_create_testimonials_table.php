<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->text('text');
            $table->string('author', 255);
            $table->string('title', 255);
            $table->string('initials', 10);
            $table->integer('rating')->default(5);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE testimonials ADD CONSTRAINT chk_testimonial_author_length CHECK (char_length(author) >= 2)');
        DB::statement('ALTER TABLE testimonials ADD CONSTRAINT chk_testimonial_initials_length CHECK (char_length(initials) >= 2 AND char_length(initials) <= 10)');
        DB::statement('ALTER TABLE testimonials ADD CONSTRAINT chk_testimonial_rating_range CHECK (rating >= 1 AND rating <= 5)');

        Schema::table('testimonials', function (Blueprint $table) {
            $table->index('author');
            $table->index('rating');
            $table->index('display_order');
            $table->index('is_active');
            $table->index('created_at');
        });

        DB::statement('CREATE INDEX idx_testimonials_active_ordered ON testimonials(is_active, display_order) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
