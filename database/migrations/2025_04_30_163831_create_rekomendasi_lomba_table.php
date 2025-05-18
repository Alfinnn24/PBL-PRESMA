<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('rekomendasi_lomba');
        Schema::create('rekomendasi_lomba', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_nim');
            $table->foreign('mahasiswa_nim')
                ->references('nim')->on('mahasiswa')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('lomba_id')->constrained('lomba')->onDelete('cascade');
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('dosen')->onDelete('cascade');
            $table->enum('status', ['Disetujui', 'Ditolak', 'Pending']);
            $table->float('skor')->nullable();
            $table->timestamps();
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
