<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranTransaksi extends Model
{
    protected $table = 'pembayaran_transaksi';

    protected $fillable = [
        'transaksi_id',
        'tanggal_bayar',
        'nominal',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'nominal' => 'float',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
