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
        Schema::create('surat_tugas_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->nullable();
            $table->foreignId('pemberi_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->foreignId('penerima_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->dateTime('tgl_awal')->nullable();
            $table->dateTime('tgl_akhir')->nullable();
            $table->string('kendaraan')->nullable();
            $table->string('no_polisi')->nullable();
            $table->foreignId('dibuat_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('dibuat_at')->nullable();
            $table->foreignId('mengetahui_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('mengetahui_at')->nullable();
            $table->foreignId('diperiksa_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('diperiksa_at')->nullable();
            $table->foreignId('disetujui_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('disetujui_at')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_keluars');
    }
};
