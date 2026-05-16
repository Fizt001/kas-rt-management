<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentSetting extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'payment_settings';

    /**
     * Atribut yang dapat diisi (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'nama_penerima',
        'qris_path',
    ];

    /**
     * Accessor untuk mendapatkan URL lengkap gambar QRIS.
     * Digunakan di view: <img src="{{ $setting->qris_url }}">
     *
     * @return string
     */
    public function getQrisUrlAttribute()
    {
        if ($this->qris_path && Storage::disk('public')->exists($this->qris_path)) {
            return asset('storage/' . $this->qris_path);
        }

        // Kembalikan gambar placeholder atau null jika tidak ada
        return 'https://ui-avatars.com/api/?name=No+QRIS&background=f1f5f9&color=cbd5e1';
    }
}