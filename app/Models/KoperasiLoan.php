<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoperasiLoan extends Model
{
    protected $fillable = ['user_id', 'amount', 'tenor', 'alasan', 'status', 'approved_at'];

    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke detail cicilan
    public function installments() {
        return $this->hasMany(KoperasiLoanInstallment::class);
    }
}