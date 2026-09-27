<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'name',
        'is_active',
        'is_locked',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_locked' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id');
    }

    public function tagihanSiswa(): HasMany
    {
        return $this->hasMany(TagihanSiswa::class, 'tahun_ajaran_id');
    }

    public function tarifPembayaran(): HasMany
    {
        return $this->hasMany(TarifPembayaran::class, 'tahun_ajaran_id');
    }

    public function potonganSiswa(): HasMany
    {
        return $this->hasMany(PotonganSiswa::class, 'tahun_ajaran_id');
    }

    public function riwayatKelasSiswa(): HasMany
    {
        return $this->hasMany(RiwayatKelasSiswa::class, 'tahun_ajaran_id');
    }
}
