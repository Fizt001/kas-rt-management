<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name', 'email', 'password', 'role', 'no_rumah', 'no_kk', 'nik', 'tanggal_lahir'
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'nik' => 'encrypted',
            'no_kk' => 'encrypted',
            'tanggal_lahir' => 'date',
        ];
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function koperasiAccount()
{
    return $this->hasOne(KoperasiAccount::class, 'user_id');
}
}
