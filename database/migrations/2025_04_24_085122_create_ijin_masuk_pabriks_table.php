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
        Schema::create('ijin_masuk_pabriks', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->nullable();
            $table->string('nama')->nullable();
            $table->dateTime('masuk')->nullable();
            $table->dateTime('keluar')->nullable();
            $table->longText('keperluan')->nullable();
            $table->string('ktp')->nullable();
            $table->string('nopol')->nullable();
            $table->string('foto_kendaraan')->nullable();
            $table->string('foto_sim')->nullable();
            $table->foreignId('dibuat_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('dibuat_at')->nullable();
            $table->foreignId('disetujui_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('disetujui_at')->nullable();
            $table->foreignId('diterima_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('diterima_at')->nullable();
            $table->foreignId('mengetahui_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('mengetahui_at')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ijin_masuk_pabriks');
    }
};
