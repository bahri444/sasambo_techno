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
        Schema::create('ekspedisi_dan_diskon', function (Blueprint $table) {
            $table->foreignId('pesanan_id');
            $table->float('berat_paket');
            $table->char('satuan_berat', 2)->default('kg');
            $table->double('tarif');
            $table->double('total_ekspedisi');
            $table->float('persentase_diskon', 3);
            $table->double('perolehan_diskon');
            $table->double('total_diskon');
            $table->double('total_semua_pesanan');
            $table->timestamps();
            $table->foreign('pesanan_id')->references('pesanan_id')->on('pesanan')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ekspedisi_dan_diskon');
    }
};
