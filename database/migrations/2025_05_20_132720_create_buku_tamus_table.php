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
        Schema::create('buku_tamus', function (Blueprint $table) {
            $table->id();
            $table->date('tgl')->nullable();
            $table->string('name')->nullable();
            $table->string('instansi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('keperluan')->nullable();
            $table->string('menemui')->nullable();
            $table->time('jam_awal')->nullable();
            $table->time('jam_akhir')->nullable();
            $table->dateTime('datang')->nullable();
            $table->dateTime('pulang')->nullable();
            $table->uuid('token')->unique()->nullable();
            $table->string('telp')->nullable();
            $table->string('id_card')->nullable();
            $table->string('surat_pengantar')->nullable();
            $table->string('foto_diri')->nullable();
            $table->string('kendaraan_tampak_depan')->nullable();
            $table->string('kendaraan_tampak_belakang')->nullable();
            $table->string('kendaraan_tampak_samping_kanan')->nullable();
            $table->string('kendaraan_tampak_samping_kiri')->nullable();
            $table->string('foto_peralatan')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->foreignId('datang_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->foreignId('pulang_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tamus');
    }
};
