<?php

use App\Models\User;
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
        Schema::create('instructor_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('adress')->nullable();
            $table->string('webiste')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('resume');
            $table->string('higest_qualification')->nullable();
            $table->string('current_ocupation')->nullable();
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_applications');
    }
};
