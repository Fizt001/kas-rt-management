<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bulan',
        'tahun',
        'total_amount',
        'status',
        'bukti_transfer',
        'verified_at',
        'verified_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}