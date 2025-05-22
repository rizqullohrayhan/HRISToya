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
        Schema::create('pjs_surat_ijins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->references('id')->on('surat_ijins')->onDelete('cascade');
            $table->foreignId('penganti_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->longText('tugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pjs_surat_ijins');
    }
};
