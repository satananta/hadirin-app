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
        Schema::disableForeignKeyConstraints();

        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->decimal('lat', 8, 6)->nullable();
            $table->decimal('long', 9, 6)->nullable();
            $table->text('lokasi')->nullable();
            $table->time('jam_datang')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->date('tanggal_absen');
            $table->enum('kategori', ["Terlambat","Tepat Waktu","Izin","Cuti"]);
            $table->enum('status', ["Hadir","Tidak Hadir"]);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absens');
    }
};
