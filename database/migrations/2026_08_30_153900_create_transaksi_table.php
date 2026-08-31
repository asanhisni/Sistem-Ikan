<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan')->nullOnDelete();
            $table->foreignId('ikan_id')->nullable()->constrained('ikan')->nullOnDelete();
            $table->string('pembeli_nama'); // nama pembeli/tempat
            $table->decimal('jumlah_kg', 10, 2)->default(0);
            $table->decimal('harga_per_kg', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status')->default('lunas'); // lunas, belum_lunas, dp
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->string('cabang')->default('Pusat');
            $table->dateTime('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
