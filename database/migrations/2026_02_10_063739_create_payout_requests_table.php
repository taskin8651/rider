<?php

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
       Schema::create('payout_requests', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('driver_id');
    $table->decimal('amount', 10, 2);
    $table->enum('status', ['pending', 'approved', 'rejected'])
          ->default('pending');
    $table->text('note')->nullable();
    $table->timestamps();

    $table->foreign('driver_id')->references('id')->on('users');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
