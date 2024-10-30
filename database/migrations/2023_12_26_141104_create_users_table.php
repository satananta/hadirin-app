<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 6);
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('password', 150);
            $table->date('tanggal_lahir');
            $table->text('photo')->nullable();
            $table->enum('jabatan', ["Manager","CEO","IT Support"]);
            $table->enum('level', ["admin","karyawan"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
