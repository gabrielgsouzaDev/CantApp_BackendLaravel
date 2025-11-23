<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->string('payment_intent_id')->unique();
        $table->enum('method', ['pix', 'card']);
        $table->integer('amount');
        $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
        $table->string('qr_code')->nullable(); // Apenas para pix
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
