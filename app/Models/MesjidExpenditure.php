<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MesjidExpenditure extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'tanggal', 'nominal', 'kategori', 'bukti_nota'];
}