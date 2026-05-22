<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up()
    {
        // 1. Change columns to TEXT to hold long encrypted strings
        Schema::table('users', function (Blueprint $table) {
            $table->text('nik')->nullable()->change();
            $table->text('no_kk')->nullable()->change();
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->text('nik')->nullable()->change();
        });

        // 2. Encrypt existing data in users table
        $users = DB::table('users')->whereNotNull('nik')->orWhereNotNull('no_kk')->get();
        foreach($users as $user) {
            $update = [];
            if (!empty($user->nik)) {
                // Check if not already encrypted (Laravel encrypt produces a very long base64 string or json)
                if (strlen($user->nik) < 100) {
                    $update['nik'] = Crypt::encryptString($user->nik);
                }
            }
            if (!empty($user->no_kk)) {
                if (strlen($user->no_kk) < 100) {
                    $update['no_kk'] = Crypt::encryptString($user->no_kk);
                }
            }
            if (!empty($update)) {
                DB::table('users')->where('id', $user->id)->update($update);
            }
        }

        // 3. Encrypt existing data in family_members table
        $families = DB::table('family_members')->whereNotNull('nik')->get();
        foreach($families as $fam) {
            if (!empty($fam->nik) && strlen($fam->nik) < 100) {
                DB::table('family_members')->where('id', $fam->id)->update([
                    'nik' => Crypt::encryptString($fam->nik)
                ]);
            }
        }
    }

    public function down()
    {
        // Decrypt data back to plain text
        $users = DB::table('users')->whereNotNull('nik')->orWhereNotNull('no_kk')->get();
        foreach($users as $user) {
            $update = [];
            if (!empty($user->nik) && strlen($user->nik) > 100) {
                try {
                    $update['nik'] = Crypt::decryptString($user->nik);
                } catch (\Exception $e) {}
            }
            if (!empty($user->no_kk) && strlen($user->no_kk) > 100) {
                try {
                    $update['no_kk'] = Crypt::decryptString($user->no_kk);
                } catch (\Exception $e) {}
            }
            if (!empty($update)) {
                DB::table('users')->where('id', $user->id)->update($update);
            }
        }

        $families = DB::table('family_members')->whereNotNull('nik')->get();
        foreach($families as $fam) {
            if (!empty($fam->nik) && strlen($fam->nik) > 100) {
                try {
                    DB::table('family_members')->where('id', $fam->id)->update([
                        'nik' => Crypt::decryptString($fam->nik)
                    ]);
                } catch (\Exception $e) {}
            }
        }

        // Change columns back to string(255)
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 255)->nullable()->change();
            $table->string('no_kk', 255)->nullable()->change();
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->string('nik', 255)->nullable()->change();
        });
    }
};
