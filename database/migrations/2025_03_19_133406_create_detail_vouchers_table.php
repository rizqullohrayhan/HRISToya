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
        Schema::create('detail_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreignId('bank_code_id')->nullable()->references('id')->on('kode_perkiraans')->onDelete('SET NULL');
            $table->foreignId('perkiraan_id')->nullable()->references('id')->on('kode_perkiraans')->onDelete('SET NULL');
            // $table->string('code')->nullable();
            // $table->string('name')->nullable();
            $table->foreignId('currency_id')->nullable()->references('id')->on('mata_uangs')->onDelete('SET NULL');
            $table->string('amount')->nullable();
            $table->string('uraian')->nullable();
            $table->string('no_bukti')->nullable();
            $table->string('tgl_bukti')->nullable();
            $table->foreignId('rekan_id')->nullable()->references('id')->on('rekanans')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_vouchers');
    }
};
