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
        Schema::create('rides', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('customer_id');
    $table->unsignedBigInteger('driver_id')->nullable();

    $table->string('pickup_location');
    $table->string('drop_location');

    $table->enum('status', [
        'pending',     // customer created
        'accepted',    // driver accepted
        'completed',   // ride done
        'cancelled'
    ])->default('pending');

    $table->timestamps();
    $table->softDeletes();

    $table->foreign('customer_id')->references('id')->on('users');
    $table->foreign('driver_id')->references('id')->on('users');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
