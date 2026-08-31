<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'pelanggan_id', 'ikan_id', 'pembeli_nama',
        'jumlah_kg', 'harga_per_kg', 'total',
        'status', 'jumlah_dibayar', 'cabang', 'tanggal_transaksi', 'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function ikan(): BelongsTo
    {
        return $this->belongsTo(Ikan::class, 'ikan_id');
    }
}
