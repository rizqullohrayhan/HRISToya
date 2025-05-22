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
        Schema::create('detail_dinas_luar_kotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->references('id')->on('dinas_luar_kotas')->onDelete('cascade');
            $table->string('instansi')->nullable();
            $table->string('menemui')->nullable();
            $table->longText('tujuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_dinas_luar_kotas');
    }
};
