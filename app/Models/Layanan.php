<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    public const JENIS_LAYANAN = ['Reguler', 'Express', 'Cargo'];

    public const ZONASI = ['Zona 1', 'Zona 2', 'Zona 3', 'Zona 4'];

    public const ESTIMASI = ['1 Hari', '2-3 Hari', '3-4 Hari', '3-5 Hari', '4-5 Hari', '5-7 Hari', '7-14 Hari'];

    public const STATUS = ['ON', 'OFF'];

    public $timestamps = false;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_ekspedisi',
        'jenis_layanan',
        'zonasi',
        'tarif',
        'estimasi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tarif' => 'integer',
        ];
    }
}
