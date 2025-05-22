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
        Schema::create('master_kontrak_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('perusahaan')->nullable();
            $table->string('customer')->nullable();
            $table->string('no_kontrak')->unique()->nullable();
            $table->string('barang')->nullable();
            $table->bigInteger('kuantitas')->nullable();
            $table->string('semester')->nullable();
            $table->string('tahun')->nullable();
            $table->date('tgl_mulai_kirim')->nullable();
            $table->string('jangka_waktu_kirim')->nullable();
            $table->string('target')->nullable();
            $table->date('batas_kirim')->nullable();
            $table->foreignId('dibuat_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('dibuat_at')->nullable();
            $table->foreignId('diperiksa_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('diperiksa_at')->nullable();
            $table->foreignId('disetujui_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('disetujui_at')->nullable();
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
        Schema::dropIfExists('master_kontrak_pengiriman');
    }
};
