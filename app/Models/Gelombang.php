<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gelombang extends Model
{
    use HasFactory;

    protected $table = 'gelombang';

    protected $fillable = [
        'nama',
        'slug',
        'harga_formulir',
        'tanggal_mulai',
        'tanggal_selesai',
        'benefit',
        'ribbon_text',
        'is_highlight',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'harga_formulir' => 'decimal:2',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'benefit' => 'array',
            'is_highlight' => 'boolean',
        ];
    }

    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class);
    }

    /**
     * True jika hari ini berada di antara tanggal_mulai dan tanggal_selesai.
     */
    public function isSedangBerlangsung(): bool
    {
        $today = now()->toDateString();

        return $this->tanggal_mulai->toDateString() <= $today
            && $this->tanggal_selesai->toDateString() >= $today;
    }
}
