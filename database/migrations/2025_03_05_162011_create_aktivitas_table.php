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
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jam_awal');
            $table->string('jam_akhir');
            $table->string('rencana')->nullable();
            $table->longText('aktivitas')->nullable();
            $table->longText('hasil')->nullable();
            $table->foreignId('rekan_id')->nullable()->references('id')->on('rekanans')->onDelete('SET NULL');
            $table->foreignId('tipe_id')->nullable()->references('id')->on('tipe_aktivitas')->onDelete('SET NULL');
            $table->foreignId('cara_id')->nullable()->references('id')->on('cara_aktivitas')->onDelete('SET NULL');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas');
    }
};
