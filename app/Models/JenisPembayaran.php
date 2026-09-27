<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPembayaran extends Model
{
    use HasFactory;

    protected $table = 'jenis_pembayaran';

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function tarifPembayaran(): HasMany
    {
        return $this->hasMany(TarifPembayaran::class, 'jenis_pembayaran_id');
    }

    public function tagihanSiswa(): HasMany
    {
        return $this->hasMany(TagihanSiswa::class, 'jenis_pembayaran_id');
    }

    public function potonganSiswa(): HasMany
    {
        return $this->hasMany(PotonganSiswa::class, 'jenis_pembayaran_id');
    }
}
