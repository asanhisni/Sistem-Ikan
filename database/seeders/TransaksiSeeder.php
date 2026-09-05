<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use App\Models\Ikan;
use App\Models\Cabang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Pelanggan
        $pelangganList = [
            ['nama' => 'bu dedeh',        'telepon' => '082127763904', 'alamat' => 'Jl. Cipagalo No. 12',      'cabang' => 'Suci'],
            ['nama' => 'Pak Haji Asep',   'telepon' => '081234567890', 'alamat' => 'Jl. Pasar Baru No. 45',    'cabang' => 'Cilawu'],
            ['nama' => 'Ibu Siti Aminah', 'telepon' => '085712345678', 'alamat' => 'Kp. Babakan No. 8',       'cabang' => 'Samarang'],
            ['nama' => 'Warung Barokah',  'telepon' => '087811223344', 'alamat' => 'Jl. Raya Garut No. 88',    'cabang' => 'Suci'],
            ['nama' => 'Resto Mina Bahari','telepon' => '081399887766','alamat' => 'Jl. Cimanuk No. 101',     'cabang' => 'Cilawu'],
            ['nama' => 'Pak Budi Santoso','telepon' => '082233445566', 'alamat' => 'Jl. Terusan Pahlawan 15', 'cabang' => 'Samarang'],
            ['nama' => 'Bu Hj. Eni',      'telepon' => '085299112233', 'alamat' => 'Jl. Pramuka No. 24',      'cabang' => 'Suci'],
            ['nama' => 'Rumah Makan Sari','telepon' => '089677889900', 'alamat' => 'Jl. Otista No. 50',        'cabang' => 'Cilawu'],
            ['nama' => 'Pak Hendra',      'telepon' => '081288776655', 'alamat' => 'Perum Permata Indah B4',  'cabang' => 'Samarang'],
            ['nama' => 'Ibu Nengsih',     'telepon' => '083812349988', 'alamat' => 'Jl. Gordah No. 17',       'cabang' => 'Suci'],
        ];

        $pelangganModels = [];
        foreach ($pelangganList as $p) {
            $pelangganModels[] = Pelanggan::firstOrCreate(
                ['nama' => $p['nama']],
                $p
            );
        }

        // 2. Data Ikan
        $ikanList = [
            ['nama_ikan' => 'Lele',     'jenis' => 'Air Tawar', 'harga_per_kg' => 24000, 'stok_kg' => 600],
            ['nama_ikan' => 'Gurame',   'jenis' => 'Air Tawar', 'harga_per_kg' => 45000, 'stok_kg' => 250],
            ['nama_ikan' => 'Nila',     'jenis' => 'Air Tawar', 'harga_per_kg' => 32000, 'stok_kg' => 400],
            ['nama_ikan' => 'Patin',    'jenis' => 'Air Tawar', 'harga_per_kg' => 28000, 'stok_kg' => 300],
            ['nama_ikan' => 'Mas',      'jenis' => 'Air Tawar', 'harga_per_kg' => 38000, 'stok_kg' => 200],
            ['nama_ikan' => 'Bawal',    'jenis' => 'Air Tawar', 'harga_per_kg' => 26000, 'stok_kg' => 220],
            ['nama_ikan' => 'Bandeng',  'jenis' => 'Air Laut',  'harga_per_kg' => 35000, 'stok_kg' => 180],
            ['nama_ikan' => 'Tongkol',  'jenis' => 'Air Laut',  'harga_per_kg' => 36000, 'stok_kg' => 150],
        ];

        $ikanModels = [];
        foreach ($ikanList as $i) {
            $ikanModels[$i['nama_ikan']] = Ikan::firstOrCreate(
                ['nama_ikan' => $i['nama_ikan']],
                $i
            );
        }

        // 3. Pastikan Cabang Tersedia
        $cabangList = Cabang::all();
        if ($cabangList->isEmpty()) {
            Cabang::firstOrCreate(['nama_cabang' => 'Suci'],     ['kode_cabang' => 'SUCI', 'nama_cabang' => 'Suci', 'lokasi' => 'Suci']);
            Cabang::firstOrCreate(['nama_cabang' => 'Cilawu'],   ['kode_cabang' => 'CLW',  'nama_cabang' => 'Cilawu', 'lokasi' => 'Cilawu']);
            Cabang::firstOrCreate(['nama_cabang' => 'Samarang'], ['kode_cabang' => 'SMR',  'nama_cabang' => 'Samarang', 'lokasi' => 'Samarang']);
            $cabangList = Cabang::all();
        }

        $cabangSuci     = $cabangList->where('nama_cabang', 'Suci')->first() ?? $cabangList->first();
        $cabangCilawu   = $cabangList->where('nama_cabang', 'Cilawu')->first() ?? $cabangList->first();
        $cabangSamarang = $cabangList->where('nama_cabang', 'Samarang')->first() ?? $cabangList->first();

        // 4. 30 Data Transaksi Realistis
        $now = Carbon::now();

        $rawTransactions = [
            // 1
            [
                'pembeli_nama' => 'bu dedeh',
                'telepon' => '082127763904',
                'cabang' => $cabangSuci,
                'status' => 'lunas',
                'days_ago' => 0,
                'time' => '08:15',
                'items' => [
                    ['ikan' => 'Lele', 'kg' => 4.00, 'harga' => 24000],
                    ['ikan' => 'Gurame', 'kg' => 3.00, 'harga' => 45000],
                    ['ikan' => 'Nila', 'kg' => 5.00, 'harga' => 32000],
                ]
            ],
            // 2
            [
                'pembeli_nama' => 'Pak Haji Asep',
                'telepon' => '081234567890',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 0,
                'time' => '09:30',
                'items' => [
                    ['ikan' => 'Gurame', 'kg' => 10.00, 'harga' => 45000],
                    ['ikan' => 'Mas', 'kg' => 8.00, 'harga' => 38000],
                ]
            ],
            // 3
            [
                'pembeli_nama' => 'Warung Barokah',
                'telepon' => '087811223344',
                'cabang' => $cabangSuci,
                'status' => 'dp',
                'dp_amount' => 300000,
                'days_ago' => 0,
                'time' => '10:45',
                'items' => [
                    ['ikan' => 'Lele', 'kg' => 25.00, 'harga' => 24000],
                    ['ikan' => 'Patin', 'kg' => 10.00, 'harga' => 28000],
                ]
            ],
            // 4
            [
                'pembeli_nama' => 'Ibu Siti Aminah',
                'telepon' => '085712345678',
                'cabang' => $cabangSamarang,
                'status' => 'lunas',
                'days_ago' => 0,
                'time' => '11:20',
                'items' => [
                    ['ikan' => 'Nila', 'kg' => 6.00, 'harga' => 32000],
                    ['ikan' => 'Bawal', 'kg' => 4.00, 'harga' => 26000],
                ]
            ],
            // 5
            [
                'pembeli_nama' => 'Pak Budi Santoso',
                'telepon' => '082233445566',
                'cabang' => $cabangSamarang,
                'status' => 'belum_lunas',
                'days_ago' => 1,
                'time' => '08:00',
                'items' => [
                    ['ikan' => 'Gurame', 'kg' => 12.00, 'harga' => 45000],
                ]
            ],
            // 6
            [
                'pembeli_nama' => 'Resto Mina Bahari',
                'telepon' => '081399887766',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 1,
                'time' => '09:15',
                'items' => [
                    ['ikan' => 'Nila', 'kg' => 30.00, 'harga' => 32000],
                    ['ikan' => 'Gurame', 'kg' => 20.00, 'harga' => 45000],
                    ['ikan' => 'Lele', 'kg' => 20.00, 'harga' => 24000],
                ]
            ],
            // 7
            [
                'pembeli_nama' => 'Bu Hj. Eni',
                'telepon' => '085299112233',
                'cabang' => $cabangSuci,
                'status' => 'lunas',
                'days_ago' => 1,
                'time' => '13:40',
                'items' => [
                    ['ikan' => 'Mas', 'kg' => 5.00, 'harga' => 38000],
                    ['ikan' => 'Nila', 'kg' => 4.00, 'harga' => 32000],
                ]
            ],
            // 8
            [
                'pembeli_nama' => 'Rumah Makan Sari',
                'telepon' => '089677889900',
                'cabang' => $cabangCilawu,
                'status' => 'dp',
                'dp_amount' => 500000,
                'days_ago' => 2,
                'time' => '08:50',
                'items' => [
                    ['ikan' => 'Patin', 'kg' => 20.00, 'harga' => 28000],
                    ['ikan' => 'Bandeng', 'kg' => 15.00, 'harga' => 35000],
                ]
            ],
            // 9
            [
                'pembeli_nama' => 'Pak Hendra',
                'telepon' => '081288776655',
                'cabang' => $cabangSamarang,
                'status' => 'lunas',
                'days_ago' => 2,
                'time' => '10:10',
                'items' => [
                    ['ikan' => 'Tongkol', 'kg' => 8.00, 'harga' => 36000],
                    ['ikan' => 'Lele', 'kg' => 6.00, 'harga' => 24000],
                ]
            ],
            // 10
            [
                'pembeli_nama' => 'Ibu Nengsih',
                'telepon' => '083812349988',
                'cabang' => $cabangSuci,
                'status' => 'lunas',
                'days_ago' => 2,
                'time' => '14:20',
                'items' => [
                    ['ikan' => 'Nila', 'kg' => 7.00, 'harga' => 32000],
                ]
            ],
            // 11
            [
                'pembeli_nama' => 'Pak Yudi',
                'telepon' => '081322114455',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 3,
                'time' => '09:00',
                'items' => [
                    ['ikan' => 'Lele', 'kg' => 15.00, 'harga' => 24000],
                    ['ikan' => 'Bawal', 'kg' => 8.00, 'harga' => 26000],
                ]
            ],
            // 12
            [
                'pembeli_nama' => 'Warung Nasi Bu Imas',
                'telepon' => '085611223399',
                'cabang' => $cabangSuci,
                'status' => 'lunas',
                'days_ago' => 3,
                'time' => '11:15',
                'items' => [
                    ['ikan' => 'Gurame', 'kg' => 8.00, 'harga' => 45000],
                    ['ikan' => 'Mas', 'kg' => 6.00, 'harga' => 38000],
                ]
            ],
            // 13
            [
                'pembeli_nama' => 'Pak Ridwan',
                'telepon' => '087799881122',
                'cabang' => $cabangSamarang,
                'status' => 'belum_lunas',
                'days_ago' => 4,
                'time' => '08:30',
                'items' => [
                    ['ikan' => 'Patin', 'kg' => 10.00, 'harga' => 28000],
                    ['ikan' => 'Nila', 'kg' => 5.00, 'harga' => 32000],
                ]
            ],
            // 14
            [
                'pembeli_nama' => 'bu dedeh',
                'telepon' => '082127763904',
                'cabang' => $cabangSuci,
                'status' => 'lunas',
                'days_ago' => 4,
                'time' => '10:00',
                'items' => [
                    ['ikan' => 'Bandeng', 'kg' => 6.00, 'harga' => 35000],
                    ['ikan' => 'Lele', 'kg' => 5.00, 'harga' => 24000],
                ]
            ],
            // 15
            [
                'pembeli_nama' => 'Katering Berkah',
                'telepon' => '081299001122',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 5,
                'time' => '07:45',
                'items' => [
                    ['ikan' => 'Nila', 'kg' => 40.00, 'harga' => 32000],
                    ['ikan' => 'Gurame', 'kg' => 15.00, 'harga' => 45000],
                ]
            ],
            // 16
            [
                'pembeli_nama' => 'Pak Haji Asep',
                'telepon' => '081234567890',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 5,
                'time' => '13:00',
                'items' => [
                    ['ikan' => 'Tongkol', 'kg' => 10.00, 'harga' => 36000],
                ]
            ],
            // 17
            [
                'pembeli_nama' => 'Bu Ratna',
                'telepon' => '082344556677',
                'cabang' => $cabangSamarang,
                'status' => 'lunas',
                'days_ago' => 6,
                'time' => '09:20',
                'items' => [
                    ['ikan' => 'Lele', 'kg' => 8.00, 'harga' => 24000],
                    ['ikan' => 'Nila', 'kg' => 4.00, 'harga' => 32000],
                ]
            ],
            // 18
            [
                'pembeli_nama' => 'Pecel Lele Mas Joko',
                'telepon' => '085811229988',
                'cabang' => $cabangSuci,
                'status' => 'dp',
                'dp_amount' => 400000,
                'days_ago' => 6,
                'time' => '15:30',
                'items' => [
                    ['ikan' => 'Lele', 'kg' => 35.00, 'harga' => 24000],
                ]
            ],
            // 19
            [
                'pembeli_nama' => 'Pak Dadan',
                'telepon' => '081388771199',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 7,
                'time' => '08:40',
                'items' => [
                    ['ikan' => 'Gurame', 'kg' => 5.00, 'harga' => 45000],
                    ['ikan' => 'Bawal', 'kg' => 5.00, 'harga' => 26000],
                ]
            ],
            // 20
            [
                'pembeli_nama' => 'Ibu Hj. Kokom',
                'telepon' => '087822338811',
                'cabang' => $cabangSamarang,
                'status' => 'lunas',
                'days_ago' => 8,
                'time' => '10:30',
                'items' => [
                    ['ikan' => 'Mas', 'kg' => 9.00, 'harga' => 38000],
                    ['ikan' => 'Patin', 'kg' => 6.00, 'harga' => 28000],
                ]
            ],
            // 21
            [
                'pembeli_nama' => 'Rumah Makan Sari',
                'telepon' => '089677889900',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 9,
                'time' => '09:50',
                'items' => [
                    ['ikan' => 'Nila', 'kg' => 20.00, 'harga' => 32000],
                    ['ikan' => 'Gurame', 'kg' => 10.00, 'harga' => 45000],
                ]
            ],
            // 22
            [
                'pembeli_nama' => 'Pak Agus Permana',
                'telepon' => '082133447788',
                'cabang' => $cabangSuci,
                'status' => 'belum_lunas',
                'days_ago' => 10,
                'time' => '11:00',
                'items' => [
                    ['ikan' => 'Bandeng', 'kg' => 12.00, 'harga' => 35000],
                ]
            ],
            // 23
            [
                'pembeli_nama' => 'Ibu Siti Aminah',
                'telepon' => '085712345678',
                'cabang' => $cabangSamarang,
                'status' => 'lunas',
                'days_ago' => 11,
                'time' => '08:10',
                'items' => [
                    ['ikan' => 'Tongkol', 'kg' => 7.00, 'harga' => 36000],
                    ['ikan' => 'Lele', 'kg' => 5.00, 'harga' => 24000],
                ]
            ],
            // 24
            [
                'pembeli_nama' => 'Warung Barokah',
                'telepon' => '087811223344',
                'cabang' => $cabangSuci,
                'status' => 'lunas',
                'days_ago' => 12,
                'time' => '14:00',
                'items' => [
                    ['ikan' => 'Lele', 'kg' => 20.00, 'harga' => 24000],
                    ['ikan' => 'Nila', 'kg' => 10.00, 'harga' => 32000],
                ]
            ],
            // 25
            [
                'pembeli_nama' => 'Pak Teten',
                'telepon' => '081299443322',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 13,
                'time' => '09:10',
                'items' => [
                    ['ikan' => 'Patin', 'kg' => 8.00, 'harga' => 28000],
                    ['ikan' => 'Mas', 'kg' => 6.00, 'harga' => 38000],
                ]
            ],
            // 26
            [
                'pembeli_nama' => 'Resto Mina Bahari',
                'telepon' => '081399887766',
                'cabang' => $cabangCilawu,
                'status' => 'lunas',
                'days_ago' => 14,
                'time' => '10:45',
                'items' => [
                    ['ikan' => 'Gurame', 'kg' => 25.00, 'harga' => 45000],
                    ['ikan' => 'Nila', 'kg' => 20.00, 'harga' => 32000],
                ]
            ],
            // 27
            [
                'pembeli_nama' => 'Bu Lilis',
                'telepon' => '085277884411',
                'cabang' => $cabangSamarang,
                'status' => 'lunas',
                'days_ago' => 15,
                'time' => '12:20',
                'items' => [
                    ['ikan' => 'Bawal', 'kg' => 6.00, 'harga' => 26000],
                    ['ikan' => 'Lele', 'kg' => 4.00, 'harga' => 24000],
                ]
            ],
            // 28
            [
                'pembeli_nama' => 'Pak Budi Santoso',
                'telepon' => '082233445566',
                'cabang' => $cabangSamarang,
                'status' => 'lunas',
                'days_ago' => 16,
                'time' => '08:55',
                'items' => [
                    ['ikan' => 'Tongkol', 'kg' => 12.00, 'harga' => 36000],
                ]
            ],
            // 29
            [
                'pembeli_nama' => 'UD. Berkah Segar',
                'telepon' => '081344556600',
                'cabang' => $cabangSuci,
                'status' => 'dp',
                'dp_amount' => 600000,
                'days_ago' => 17,
                'time' => '13:15',
                'items' => [
                    ['ikan' => 'Bandeng', 'kg' => 20.00, 'harga' => 35000],
                    ['ikan' => 'Gurame', 'kg' => 10.00, 'harga' => 45000],
                ]
            ],
            // 30
            [
                'pembeli_nama' => 'bu dedeh',
                'telepon' => '082127763904',
                'cabang' => $cabangSuci,
                'status' => 'lunas',
                'days_ago' => 18,
                'time' => '15:00',
                'items' => [
                    ['ikan' => 'Nila', 'kg' => 8.00, 'harga' => 32000],
                    ['ikan' => 'Mas', 'kg' => 5.00, 'harga' => 38000],
                ]
            ],
        ];

        // 5. Masukkan ke Database
        foreach ($rawTransactions as $tx) {
            $totalKg = 0;
            $totalHarga = 0;
            $detailsData = [];
            $primaryIkanId = null;

            foreach ($tx['items'] as $it) {
                $ikanModel = $ikanModels[$it['ikan']] ?? Ikan::first();
                if (!$primaryIkanId && $ikanModel) {
                    $primaryIkanId = $ikanModel->id;
                }

                $subtotal = $it['kg'] * $it['harga'];
                $totalKg += $it['kg'];
                $totalHarga += $subtotal;

                $detailsData[] = [
                    'ikan_id'      => $ikanModel ? $ikanModel->id : null,
                    'jumlah_kg'    => $it['kg'],
                    'harga_per_kg' => $it['harga'],
                    'subtotal'     => $subtotal,
                ];
            }

            // Cari pelanggan_id jika cocok dengan nama
            $matchedPelanggan = Pelanggan::where('nama', $tx['pembeli_nama'])->first();

            // Hitung jumlah dibayar
            if ($tx['status'] === 'lunas') {
                $dibayar = $totalHarga;
            } elseif ($tx['status'] === 'dp') {
                $dibayar = $tx['dp_amount'] ?? round($totalHarga * 0.4);
            } else {
                $dibayar = 0;
            }

            [$hours, $minutes] = explode(':', $tx['time']);
            $txDate = $now->copy()->subDays($tx['days_ago'])->setTime((int)$hours, (int)$minutes, 0);

            $cabangObj = $tx['cabang'];

            $transaksi = Transaksi::create([
                'pelanggan_id'      => $matchedPelanggan ? $matchedPelanggan->id : null,
                'ikan_id'           => $primaryIkanId,
                'pembeli_nama'      => $tx['pembeli_nama'],
                'telepon'           => $tx['telepon'],
                'jumlah_kg'         => $totalKg,
                'harga_per_kg'      => count($tx['items']) > 0 ? round($totalHarga / $totalKg) : 0,
                'total'             => $totalHarga,
                'status'            => $tx['status'],
                'jumlah_dibayar'    => $dibayar,
                'cabang'            => $cabangObj->nama_cabang,
                'cabang_id'         => $cabangObj->id,
                'tanggal_transaksi' => $txDate,
                'keterangan'        => 'Transaksi ' . $tx['pembeli_nama'],
            ]);

            // Buat Transaksi Detail
            foreach ($detailsData as $d) {
                $d['transaksi_id'] = $transaksi->id;
                TransaksiDetail::create($d);
            }
        }
    }
}
