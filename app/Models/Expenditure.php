<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    protected $fillable = ['nama_agenda', 'butuh_dana', 'nominal', 'bukti_kegiatan', 'nota_belanja'];
}
