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
        // Tabel penilaian
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('jembatan_kode');
            $table->string('komponen_kode');
            $table->year('tahun');
            $table->string('nilai');
            $table->timestamps();

            $table->foreign('jembatan_kode')->references('kode_jembatan')->on('jembatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('komponen_kode')->references('kode_komponen')->on('komponens')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
