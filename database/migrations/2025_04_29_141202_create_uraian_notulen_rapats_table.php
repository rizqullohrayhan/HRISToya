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
        Schema::create('uraian_notulen_rapats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notulen_rapat_id')->constrained('notulen_rapats')->onDelete('cascade');
            $table->longText('uraian')->nullable();
            $table->longText('action')->nullable();
            $table->date('due_date')->nullable();
            $table->string('pic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uraian_notulen_rapats');
    }
};
