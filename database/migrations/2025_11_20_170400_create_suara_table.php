<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suara', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key ke Pemilih
            $table->foreignId('pemilih_id')
                  ->unique() // Satu pemilih hanya boleh satu suara
                  ->constrained('pemilih')
                  ->onDelete('cascade');

            // Data denormalisasi (sesuai SQL dump kamu)
            // Catatan: Sebaiknya data ini diambil via relasi, tapi saya buat sesuai SQL asli
            $table->string('nim', 20); // Di SQL int(9), tapi NIM biasanya string
            $table->string('nama', 64);
            $table->string('fakultas', 10); // Di SQL varchar(4), saya besarkan sedikit
            $table->string('program_studi', 64);

            // Foreign Key ke Kandidat
            $table->foreignId('kandidat_id')
                  ->constrained('kandidat')
                  ->onDelete('cascade');

            $table->timestamp('waktu_vote')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suara');
    }
};