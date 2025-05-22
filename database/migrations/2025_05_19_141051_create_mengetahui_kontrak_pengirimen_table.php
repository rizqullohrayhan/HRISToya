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
        Schema::create('mengetahui_kontrak_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_kontrak_pengiriman_id')->constrained('master_kontrak_pengiriman')->onDelete('cascade')->name('fk_mengetahui_mk_pengiriman');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mengetahui_kontrak_pengiriman');
    }
};
