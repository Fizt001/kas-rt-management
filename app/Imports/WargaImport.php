<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WargaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    return new \App\Models\User([
        'name'     => $row['nama'],
        'email'    => $row['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($row['password'] ?? 'warga123'),
        'role'     => $row['role'] ?? 'warga', // Ambil role dari Excel, default 'warga'
        'foto'     => $row['nama_file_foto'] ?? null,
    ]);
}
}