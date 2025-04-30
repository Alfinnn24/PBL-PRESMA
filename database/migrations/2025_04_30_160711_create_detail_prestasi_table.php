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
        Schema::dropIfExists('detail_prestasi');
        Schema::create('detail_prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_nim');
            $table->foreignId('prestasi_id')->constrained('prestasi');
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_prestasi');
    }
};
