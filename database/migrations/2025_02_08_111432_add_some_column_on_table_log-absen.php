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
        Schema::table('log_absens', function (Blueprint $table) {
            $table->string('status_absen')->nullable()->after('clock_out');
            $table->string('jenis_izin')->nullable()->after('status_absen');
            $table->string('alasan_izin')->nullable()->after('jenis_izin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
