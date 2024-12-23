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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable()->default(null);
            $table->enum('type', ['income', 'expense']);
            $table->enum('classification', [
            'Moradia',
            'Dívida',
            'Cartões',
            'Carro',
            'Lilith']);
            $table->string('description', 400);
            $table->text('payment_code')->nullable();
            $table->timestamp('payed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
