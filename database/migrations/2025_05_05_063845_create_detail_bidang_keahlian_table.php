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
        Schema::create('detail_bidang_keahlian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_keahlian')->constrained('bidang_keahlian')->onDelete('cascade');
            $table->string('mahasiswa_nim');
            $table->foreign('mahasiswa_nim')
                ->references('nim')->on('mahasiswa')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_bidang_keahlian');
    }
};
