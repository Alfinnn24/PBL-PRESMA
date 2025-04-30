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
        Schema::create('rekomendasi_lomba', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_nim');
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswa');
            $table->foreignId('lomba_id')->constrained('lomba');
            $table->foreignId('dosen_pembimbing_id')->constrained('dosen');
            $table->enum('status', ['Disetujui', 'Ditolak']);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_lomba');
    }
};
