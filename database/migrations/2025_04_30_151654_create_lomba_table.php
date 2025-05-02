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
        Schema::dropIfExists('lomba');
        Schema::create('lomba', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori');
            $table->string('penyelenggara');
            $table->enum('tingkat', ['Kota/Kabupaten', 'Provinsi', 'Nasional', 'Internasional']);
            $table->text('bidang_keahlian');
            $table->text('persyaratan');
            $table->string('link_registrasi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->foreignId('periode_id')->constrained('periode');
            $table->foreignId('created_by')->constrained('user');
            $table->boolean('is_verified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lomba');
    }
};
