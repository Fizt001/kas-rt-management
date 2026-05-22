<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'user_id', 'nama', 'status_hubungan', 'nik', 'kelompok_kk', 'no_kk_kelompok', 'tanggal_lahir'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nik' => 'encrypted',
            'no_kk_kelompok' => 'encrypted',
            'tanggal_lahir' => 'date',
        ];
    }
}
