<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'mapel',
        'jurusan_id',
        'is_pimpinan',
        'jabatan',
        'foto',
        'is_active',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'is_pimpinan' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Jurusan di mana guru ini menjabat sebagai kaprodi (kebalikan dari
     * Jurusan::kaprodi()). Satu guru maksimal jadi kaprodi satu jurusan.
     */
    public function jurusanSebagaiKaprodi(): HasOne
    {
        return $this->hasOne(Jurusan::class, 'kaprodi_guru_id');
    }

    public function rekomendasiPendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class, 'rekomendasi_guru_id');
    }
}
