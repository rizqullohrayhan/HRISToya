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
        Schema::create('rencana_pengirimen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_kebun_pengiriman_id')->constrained('rekap_kebun_pengiriman')->onDelete('cascade');
            $table->date('tgl')->nullable();
            $table->string('nopol')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_pengirimen');
    }
};
