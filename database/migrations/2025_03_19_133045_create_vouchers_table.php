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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            // $table->timestamp('created_at')->nullable();
            $table->date('tanggal')->nullable();
            $table->foreignId('status_id')->nullable()->references('id')->on('status_vouchers')->onDelete('SET NULL');
            $table->string('no_voucher')->nullable();
            $table->foreignId('bank_code_id')->nullable()->references('id')->on('kode_perkiraans')->onDelete('SET NULL');
            $table->foreignId('rekan_id')->nullable()->references('id')->on('rekanans')->onDelete('SET NULL');
            $table->longText('pay_for')->nullable();
            $table->string('bukti')->nullable();
            $table->string('file')->nullable();
            $table->foreignId('tipe_id')->nullable()->references('id')->on('tipe_vouchers')->onDelete('SET NULL');
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('set_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('bookkeeped_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('bookkeeped_at')->nullable();
            $table->foreignId('approved_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
