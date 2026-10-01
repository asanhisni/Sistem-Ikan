<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'pelanggan_id', 'ikan_id', 'pembeli_nama', 'telepon',
        'jumlah_kg', 'harga_per_kg', 'total',
        'status', 'jumlah_dibayar', 'cabang', 'cabang_id', 'tanggal_transaksi', 'keterangan',
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

    public function details(): HasMany
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(PembayaranTransaksi::class, 'transaksi_id')->orderBy('tanggal_bayar', 'asc')->orderBy('id', 'asc');
    }

    public function cabangRelasi(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function getTotalDibayarAttribute(): float
    {
        if ($this->relationLoaded('pembayarans') && $this->pembayarans->isNotEmpty()) {
            return (float) $this->pembayarans->sum('nominal');
        }
        return (float) ($this->jumlah_dibayar ?? 0);
    }

    public function getSisaTagihanAttribute(): float
    {
        $sisa = (float) $this->total - $this->total_dibayar;
        return max(0, $sisa);
    }
}
