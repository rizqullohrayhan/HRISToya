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
        Schema::create('rekap_kebun_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_kontrak_pengiriman_id')->constrained('master_kontrak_pengiriman')->onDelete('cascade');
            $table->string('vendor')->nullable();
            $table->string('kebun')->nullable();
            $table->string('kontrak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kebun_pengiriman');
    }
};
