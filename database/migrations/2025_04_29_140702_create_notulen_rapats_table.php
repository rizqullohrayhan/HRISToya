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
        Schema::create('notulen_rapats', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->nullable();
            $table->dateTime('tanggal')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('pimpinan')->nullable();
            $table->longText('agenda')->nullable();
            $table->longText('agenda_plain')->nullable();
            $table->integer('tipe')->nullable();
            $table->string('picture')->nullable();
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
        Schema::dropIfExists('notulen_rapats');
    }
};
