<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        // Bawaan Agenda
        'user_id',
        'judul',
        'deskripsi',
        'tanggal',
        'waktu',
        'lokasi',
        'status',
        
        // Tambahan Penggunaan Dana
        'realisasi_dana',
        'bukti_kegiatan',
        'nota_belanja',
    ];

    // Relasi bawaanmu
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}