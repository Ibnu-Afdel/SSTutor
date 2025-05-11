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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('payment_method', ['manual', 'chapa', 'arifpay']);
            $table->enum('status', ['active', 'pending', 'expired', 'rejected'])->default('pending');
            $table->string('amount');
            $table->integer('duration_in_days');
            $table->json('screenshot_path')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('paid_at')->nullable();
            $table->string('starts_at')->nullable();
            $table->string('expires_at')->nullable();
            $table->string('notes')->nullable(); //zis is for  admin message
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
