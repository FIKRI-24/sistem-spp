<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TagihanSiswa extends Model
{
    use HasFactory;

    protected $table = 'tagihan_siswa';

    protected $fillable = [
        'siswa_id',
        'jenis_pembayaran_id',
        'tahun_ajaran_id',
        'period',
        'amount',
        'amount_paid',
        'status',
        'due_date',
        'void_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    /**
     * Accessor computed attribute untuk status tagihan (PRD Bab 10.7).
     * Mencegah duplikasi logika status di tempat lain.
     */
    protected function calculatedStatus(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->status === 'void') {
                    return 'void';
                }

                $paid = (float) $this->amount_paid;
                $amount = (float) $this->amount;

                if ($paid <= 0) {
                    return 'unpaid';
                }

                if ($paid < $amount) {
                    return 'partial';
                }

                return 'paid';
            }
        );
    }

    /**
     * Accessor untuk sisa tagihan yang belum dibayar.
     */
    protected function remainingAmount(): Attribute
    {
        return Attribute::make(
            get: fn (): float => max(0, (float) $this->amount - (float) $this->amount_paid)
        );
    }

    /**
     * Label bahasa Indonesia untuk status tagihan.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return match ($this->calculated_status) {
                    'paid' => 'Lunas',
                    'partial' => 'Sebagian',
                    'void' => 'Dibatalkan',
                    default => 'Belum Lunas',
                };
            }
        );
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function jenisPembayaran(): BelongsTo
    {
        return $this->belongsTo(JenisPembayaran::class, 'jenis_pembayaran_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class, 'tagihan_siswa_id');
    }
}
