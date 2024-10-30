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

        Schema::create('suratizins', function (Blueprint $table) {
            $table->id();
            $table->text('keterangan');
            $table->text('keterangan_admin')->nullable();
            $table->text('file_izin');
            $table->date('tanggal_izin');
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
        Schema::dropIfExists('suratizins');
    }
};
