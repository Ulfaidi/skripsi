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
        // Tabel jembatan
        Schema::create('jembatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jembatan')->unique();
            $table->string('nama_jembatan', 150);
            $table->string('kecamatan_satu_kode');
            $table->string('kecamatan_dua_kode');
            $table->string('desa_satu_kode');
            $table->string('desa_dua_kode');
            $table->year('tahun_pembangunan');
            $table->string('panjang');
            $table->string('lebar');
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('kecamatan_satu_kode')->references('kode_kecamatan')->on('kecamatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kecamatan_dua_kode')->references('kode_kecamatan')->on('kecamatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('desa_satu_kode')->references('kode_desa')->on('desas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('desa_dua_kode')->references('kode_desa')->on('desas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jembatans');
    }
};
