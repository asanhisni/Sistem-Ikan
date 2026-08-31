<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            // Jika true, admin bisa merangkap sebagai kepala cabang ini
            $table->boolean('admin_merangkap')->default(false)->after('lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropColumn('admin_merangkap');
        });
    }
};
