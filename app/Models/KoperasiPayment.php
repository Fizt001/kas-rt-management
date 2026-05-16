<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoperasiPayment extends Model
{
   protected $fillable = ['nama_bank', 'nomor_rekening', 'atas_nama', 'qris_image'];
}
