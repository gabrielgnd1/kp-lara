<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJumlahOrangToPemesananFasilitas extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('pemesanan_fasilitas', 'jumlah_orang')) {
            Schema::table('pemesanan_fasilitas', function (Blueprint $table) {
                $table->integer('jumlah_orang')->default(1)->after('selesai');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_fasilitas', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanan_fasilitas', 'jumlah_orang')) {
                $table->dropColumn('jumlah_orang');
            }
        });
    }
}
