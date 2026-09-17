<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokCabang extends Model
{
    protected $table = 'stok_cabang';

    protected $fillable = ['cabang_id', 'ikan_id', 'stok_kg'];

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function ikan(): BelongsTo
    {
        return $this->belongsTo(Ikan::class, 'ikan_id');
    }
}
