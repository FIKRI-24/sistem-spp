<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterTransaksi extends Model
{
    use HasFactory;

    protected $table = 'counter_transaksi';

    protected $fillable = [
        'date',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'last_number' => 'integer',
        ];
    }
}
