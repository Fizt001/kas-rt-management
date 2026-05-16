<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IuranMaster extends Model
{
    // Nama tabel di database
    protected $table = 'iuran_masters';

    // Kolom yang boleh diisi masal
    protected $fillable = [
        'nama_iuran', 
        'nominal', 
        'deskripsi', 
        'is_active'
    ];

    // Helper untuk format rupiah di tabel
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}