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

        Schema::create('suratcutis', function (Blueprint $table) {
            $table->id();
            $table->text('keterangan');
            $table->text('keterangan_admin')->nullable();
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->enum('status', ["Pending","Terima","Tolak"]);
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
        Schema::dropIfExists('suratcutis');
    }
};
