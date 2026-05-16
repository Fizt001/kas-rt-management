<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoperasiTransaction extends Model
{
    // 1. TAMBAHKAN BARIS INI UNTUK MEMBERI IZIN MASS ASSIGNMENT:
    protected $fillable = [
        'user_id', 
        'type', 
        'kategori', 
        'amount', 
        'keterangan', 
        'status', 
        'bukti_transfer'
    ];

    // 2. Relasi balik ke User
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}