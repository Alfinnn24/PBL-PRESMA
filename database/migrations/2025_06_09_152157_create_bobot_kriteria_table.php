<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bobot_kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kriteria')->unique(); // contoh: sertifikasi, keahlian, pengalaman, prestasi
            $table->decimal('bobot', 5, 4); // contoh: 0.25
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bobot_kriteria');
    }
};
