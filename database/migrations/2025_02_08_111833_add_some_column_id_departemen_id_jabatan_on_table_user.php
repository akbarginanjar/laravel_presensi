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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_departemen')->nullable()->after('is_login');
            $table->unsignedBigInteger('id_jabatan')->nullable()->after('id_departemen');;
            $table->foreign('id_departemen')->references('id')->on('departemens');
            $table->foreign('id_jabatan')->references('id')->on('jabatans');
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
