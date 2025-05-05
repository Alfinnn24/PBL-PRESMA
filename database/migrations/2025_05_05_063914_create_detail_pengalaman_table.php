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
        Schema::create('detail_pengalaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengalaman')->constrained('pengalaman')->onDelete('cascade');
            $table->string('mahasiswa_nim');
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengalaman');
    }
};
