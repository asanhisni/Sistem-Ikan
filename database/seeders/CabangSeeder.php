<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = [
            ['kode_cabang' => 'SUCI', 'nama_cabang' => 'Suci', 'lokasi' => 'Suci'],
            ['kode_cabang' => 'CLW', 'nama_cabang' => 'Cilawu', 'lokasi' => 'Cilawu'],
            ['kode_cabang' => 'SMR', 'nama_cabang' => 'Samarang', 'lokasi' => 'Samarang'],
            ['kode_cabang' => 'CBG1', 'nama_cabang' => 'Cabang 1', 'lokasi' => 'Cabang 1'],
        ];

        foreach ($cabangs as $c) {
            Cabang::firstOrCreate(['nama_cabang' => $c['nama_cabang']], $c);
        }
    }
}
