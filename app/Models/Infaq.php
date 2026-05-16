<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Infaq extends Model
{
    protected $fillable = [
        'user_id', 
        'nominal', 
        'kategori', 
        'bukti_transfer', 
        'pesan_doa', 
        'status' // Tambahkan ini kalau belum ada
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}