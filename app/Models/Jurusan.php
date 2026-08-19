<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'keunggulan',
        'icon',
        'foto',
        'akreditasi',
        'kaprodi_guru_id',
        'is_active',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'keunggulan' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function kaprodi(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'kaprodi_guru_id');
    }

    public function guru(): HasMany
    {
        return $this->hasMany(Guru::class);
    }

    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class);
    }

    public function alumni(): HasMany
    {
        return $this->hasMany(Alumni::class);
    }
}
