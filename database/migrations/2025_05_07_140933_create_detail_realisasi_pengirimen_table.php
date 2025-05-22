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
        Schema::create('detail_realisasi_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_kebun_pengiriman_id')->constrained('rekap_kebun_pengiriman')->onDelete('cascade');
            $table->date('tgl')->nullable();
            $table->string('nopol')->nullable();
            $table->string('no_sj')->nullable();
            $table->string('no_so_pkt')->nullable();
            $table->string('vendor')->nullable();
            $table->bigInteger('kirim')->nullable();
            $table->bigInteger('terima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_realisasi_pengiriman');
    }
};
