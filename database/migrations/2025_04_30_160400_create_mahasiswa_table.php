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
        Schema::dropIfExists('mahasiswa');
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim')->primary();
            $table->foreignId('user_id')->constrained('user');
            $table->string('nama_lengkap');
            $table->year('angkatan');
            $table->string('no_telp');
            $table->string('alamat');
            $table->foreignId('program_studi_id')->constrained('program_studi');
            $table->text('bidang_keahlian');
            $table->text('sertifikasi');
            $table->text('pengalaman');
            $table->string('foto_profile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
