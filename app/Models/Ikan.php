<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ikan extends Model
{
    protected $table = 'ikan';

    protected $fillable = ['nama_ikan', 'jenis', 'harga_per_kg', 'stok_kg', 'satuan'];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'ikan_id');
    }
}
