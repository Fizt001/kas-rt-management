<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoperasiAccount extends Model
{
    protected $fillable = [
        'user_id', 
        'saldo_pokok', 
        'saldo_wajib', 
        'saldo_sukarela', 
        'total_saldo'
    ];
public function user() {
    return $this->belongsTo(User::class);
}
}
