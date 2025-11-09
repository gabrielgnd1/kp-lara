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
        Schema::table('reservasi', function (Blueprint $table) {
            // File paths untuk upload
            $table->string('file_reservation_form', 255)->nullable()->after('status_pembayaran');
            $table->string('file_bukti_dp', 255)->nullable()->after('file_reservation_form');
            $table->string('file_bukti_lunas', 255)->nullable()->after('file_bukti_dp');
            // Tipe pembayaran yang dipilih (VARCHAR instead of ENUM for compatibility)
            $table->string('tipe_pembayaran', 10)->nullable()->after('file_bukti_lunas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn(['file_reservation_form', 'file_bukti_dp', 'file_bukti_lunas', 'tipe_pembayaran']);
        });
    }
};
