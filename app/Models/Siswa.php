<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nisn',
        'name',
        'gender',
        'kelas_id',
        'entry_date',
        'status',
        'parent_name',
        'parent_phone',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tagihanSiswa(): HasMany
    {
        return $this->hasMany(TagihanSiswa::class, 'siswa_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'siswa_id');
    }

    public function riwayatKelasSiswa(): HasMany
    {
        return $this->hasMany(RiwayatKelasSiswa::class, 'siswa_id');
    }

    public function potonganSiswa(): HasMany
    {
        return $this->hasMany(PotonganSiswa::class, 'siswa_id');
    }

    public function akunPortal(): HasOne
    {
        return $this->hasOne(AkunPortal::class, 'siswa_id');
    }
}
