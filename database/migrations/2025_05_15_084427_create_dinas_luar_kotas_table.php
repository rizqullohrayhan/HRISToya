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
        Schema::create('dinas_luar_kotas', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->nullable();
            $table->foreignId('penerima_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->foreignId('pemberi_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->string('kendaraan')->nullable();
            $table->string('no_polisi')->nullable();
            $table->string('kota')->nullable();
            $table->string('jangka_waktu')->nullable();
            $table->string('satuan_waktu')->nullable();
            $table->dateTime('berangkat')->nullable();
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
        Schema::dropIfExists('dinas_luar_kotas');
    }
};
