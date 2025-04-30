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
        Schema::dropIfExists('dosen_pembimbing');
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosen');
            $table->string('mahasiswa_nim');
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pembimbing');
    }
};
