<?php

use App\Models\Instructor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_pro')->default(false);
            $table->string('name');
            $table->text('description');
            $table->string('level')->default('beginner');
            $table->string('status')->default('draft');

            // Monetization-related (nullable for now..)
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('original_price', 8, 2)->nullable();
            $table->boolean('discount')->default(false);
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 8, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('enrollment_limit')->nullable();

            // Learning info
            $table->integer('duration')->nullable();
            $table->text('requirements')->nullable();
            $table->longText('syllabus')->nullable();
            $table->decimal('rating', 2, 1)->nullable();

            // Relationships
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
