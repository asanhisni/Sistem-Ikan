<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use App\Models\Ikan;
use App\Models\Transaksi;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // Pelanggan
        $pelangganData = [
            ['nama' => 'Toko Makmur',   'telepon' => '081234567890', 'alamat' => 'Jl. Pasar Baru No.1',  'cabang' => 'Pusat'],
            ['nama' => 'Bapak H. Asep', 'telepon' => '082345678901', 'alamat' => 'Jl. Merdeka No.5',    'cabang' => 'Cabang 1'],
            ['nama' => 'Pasar Sentral', 'telepon' => '083456789012', 'alamat' => 'Jl. Sentral No.10',   'cabang' => 'Pusat'],
            ['nama' => 'Ibu Ani',       'telepon' => '084567890123', 'alamat' => 'Jl. Kenanga No.3',    'cabang' => 'Cabang 2'],
            ['nama' => 'UD. Berkah',    'telepon' => '085678901234', 'alamat' => 'Jl. Raya No.8',       'cabang' => 'Pusat'],
            ['nama' => 'Pak Budi',      'telepon' => '086789012345', 'alamat' => 'Jl. Melati No.15',    'cabang' => 'Cabang 1'],
        ];

        foreach ($pelangganData as $p) {
            Pelanggan::firstOrCreate(['nama' => $p['nama']], $p);
        }

        // Ikan
        $ikanData = [
            ['nama_ikan' => 'Lele',     'jenis' => 'Air Tawar', 'harga_per_kg' => 18000,  'stok_kg' => 500],
            ['nama_ikan' => 'Gurame',   'jenis' => 'Air Tawar', 'harga_per_kg' => 35000,  'stok_kg' => 200],
            ['nama_ikan' => 'Nila',     'jenis' => 'Air Tawar', 'harga_per_kg' => 22000,  'stok_kg' => 350],
            ['nama_ikan' => 'Bandeng',  'jenis' => 'Air Laut',  'harga_per_kg' => 28000,  'stok_kg' => 150],
            ['nama_ikan' => 'Tongkol',  'jenis' => 'Air Laut',  'harga_per_kg' => 32000,  'stok_kg' => 180],
        ];

        foreach ($ikanData as $i) {
            Ikan::firstOrCreate(['nama_ikan' => $i['nama_ikan']], $i);
        }

        // Transaksi demo sesuai gambar desain
        $today = Carbon::today();
        $transaksiData = [
            [
                'pelanggan_id'      => 1,
                'ikan_id'           => 1,
                'pembeli_nama'      => 'Toko Makmur',
                'jumlah_kg'         => 150,
                'harga_per_kg'      => 30000,
                'total'             => 4500000,
                'status'            => 'lunas',
                'jumlah_dibayar'    => 4500000,
                'cabang'            => 'Pusat',
                'tanggal_transaksi' => $today->copy()->setTime(8, 30),
            ],
            [
                'pelanggan_id'      => 2,
                'ikan_id'           => 3,
                'pembeli_nama'      => 'Bapak H. Asep',
                'jumlah_kg'         => 25,
                'harga_per_kg'      => 34000,
                'total'             => 850000,
                'status'            => 'belum_lunas',
                'jumlah_dibayar'    => 0,
                'cabang'            => 'Cabang 1',
                'tanggal_transaksi' => $today->copy()->setTime(9, 15),
            ],
            [
                'pelanggan_id'      => 3,
                'ikan_id'           => 2,
                'pembeli_nama'      => 'Pasar Sentral',
                'jumlah_kg'         => 500,
                'harga_per_kg'      => 28000,
                'total'             => 14000000,
                'status'            => 'lunas',
                'jumlah_dibayar'    => 14000000,
                'cabang'            => 'Pusat',
                'tanggal_transaksi' => $today->copy()->setTime(10, 0),
            ],
            [
                'pelanggan_id'      => 4,
                'ikan_id'           => 1,
                'pembeli_nama'      => 'Ibu Ani',
                'jumlah_kg'         => 10,
                'harga_per_kg'      => 35000,
                'total'             => 350000,
                'status'            => 'dp',
                'jumlah_dibayar'    => 150000,
                'cabang'            => 'Cabang 2',
                'tanggal_transaksi' => $today->copy()->setTime(11, 20),
            ],
            [
                'pelanggan_id'      => 5,
                'ikan_id'           => 4,
                'pembeli_nama'      => 'UD. Berkah',
                'jumlah_kg'         => 300,
                'harga_per_kg'      => 30000,
                'total'             => 9000000,
                'status'            => 'lunas',
                'jumlah_dibayar'    => 9000000,
                'cabang'            => 'Pusat',
                'tanggal_transaksi' => $today->copy()->subDays(1)->setTime(8, 0),
            ],
            [
                'pelanggan_id'      => 6,
                'ikan_id'           => 5,
                'pembeli_nama'      => 'Pak Budi',
                'jumlah_kg'         => 50,
                'harga_per_kg'      => 32000,
                'total'             => 1600000,
                'status'            => 'dp',
                'jumlah_dibayar'    => 500000,
                'cabang'            => 'Cabang 1',
                'tanggal_transaksi' => $today->copy()->subDays(2)->setTime(14, 30),
            ],
        ];

        foreach ($transaksiData as $t) {
            Transaksi::create($t);
        }
    }
}
