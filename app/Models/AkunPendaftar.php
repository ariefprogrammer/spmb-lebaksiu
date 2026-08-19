<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AkunPendaftar extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akun_pendaftar';

    protected $fillable = [
        'nama',
        'no_telepon',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class);
    }
}
