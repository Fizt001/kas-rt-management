<?php
use App\Models\User;

$users = User::where('role', 'warga')->get();

foreach ($users as $user) {
    $noRumahRaw = $user->no_rumah;
    
    if (empty($user->blok_rumah) && !empty($noRumahRaw)) {
        // Cek apakah mengandung "/"
        if (strpos($noRumahRaw, '/') !== false) {
            $parts = explode('/', $noRumahRaw);
            if (count($parts) == 2) {
                $user->blok_rumah = str_replace(' ', '', strtoupper($parts[0]));
                $user->no_rumah = str_replace(' ', '', strtoupper($parts[1]));
                $user->save();
                echo "Updated user {$user->name}: Blok {$user->blok_rumah}, No {$user->no_rumah}\n";
            }
        } else {
            // Hapus spasi juga kalau sudah dipisah sebelumnya
            $user->no_rumah = str_replace(' ', '', strtoupper($noRumahRaw));
            $user->save();
        }
    } else {
        // Jika sudah ada blok rumah, pastikan bersih dari spasi
        if ($user->blok_rumah) {
            $user->blok_rumah = str_replace(' ', '', strtoupper($user->blok_rumah));
        }
        if ($user->no_rumah) {
            $user->no_rumah = str_replace(' ', '', strtoupper($user->no_rumah));
        }
        $user->save();
    }
}

echo "Proses pembersihan selesai.\n";
