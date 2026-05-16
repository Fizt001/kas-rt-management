<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoperasiLoanInstallment extends Model
{
    protected $fillable = ['koperasi_loan_id', 'cicilan_ke', 'amount', 'jatuh_tempo', 'status', 'bukti_transfer', 'paid_at'];

    // Relasi balik ke Induk Pinjaman
    public function loan() {
        return $this->belongsTo(KoperasiLoan::class, 'koperasi_loan_id');
    }
}