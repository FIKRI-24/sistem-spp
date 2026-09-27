<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengaturanSekolah extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_sekolah';

    protected $fillable = [
        'school_name',
        'address',
        'phone',
        'email',
        'logo_path',
        'receipt_header_note',
        'receipt_footer_note',
        'bank_account_info',
        'transaction_number_format',
        'active_tahun_ajaran_id',
        'auto_create_portal_account',
    ];

    protected function casts(): array
    {
        return [
            'auto_create_portal_account' => 'boolean',
        ];
    }

    public function activeTahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'active_tahun_ajaran_id');
    }
}
