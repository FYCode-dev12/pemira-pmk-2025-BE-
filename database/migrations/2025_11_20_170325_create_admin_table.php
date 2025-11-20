<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->timestamp('created_at')->useCurrent();
            // Laravel biasanya butuh updated_at, jika tidak mau, abaikan timestamps()
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin');
    }
};