<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembayaran extends Model
{
    use HasFactory;

    protected $table = 'detail_pembayaran';

    protected $fillable = [
        'pembayaran_id',
        'tagihan_siswa_id',
        'amount_allocated',
    ];

    protected function casts(): array
    {
        return [
            'amount_allocated' => 'decimal:2',
        ];
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    public function tagihanSiswa(): BelongsTo
    {
        return $this->belongsTo(TagihanSiswa::class, 'tagihan_siswa_id');
    }
}
