<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemilih', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->string('fakultas', 100);
            $table->string('program_studi', 100);
            $table->string('token', 64)->nullable()->unique();
            $table->boolean('sudah_memilih')->default(false);
            $table->timestamp('waktu_memilih')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemilih');
    }
};