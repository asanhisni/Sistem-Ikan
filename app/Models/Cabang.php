<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    protected $table = 'cabang';

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'lokasi',
        'admin_merangkap',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'cabang_id');
    }
}
