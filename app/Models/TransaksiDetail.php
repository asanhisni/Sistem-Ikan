<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_details';

    protected $fillable = [
        'transaksi_id',
        'ikan_id',
        'jumlah_kg',
        'harga_per_kg',
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function ikan()
    {
        return $this->belongsTo(Ikan::class, 'ikan_id');
    }
}
