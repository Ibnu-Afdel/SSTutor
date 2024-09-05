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
            $table->string('name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->boolean('discount')->default(false);
            $table->decimal('rating', 2, 1)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('duration')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('enrollment_limit')->nullable();
            $table->text('requirements')->nullable();
            $table->longText('syllabus')->nullable();
            $table->foreignIdFor(Instructor::class)->constrained()->cascadeOnDelete();  
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
