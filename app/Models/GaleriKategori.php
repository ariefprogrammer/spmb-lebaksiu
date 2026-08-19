<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GaleriKategori extends Model
{
    use HasFactory;

    protected $table = 'galeri_kategori';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'slug',
        'urutan',
    ];

    public function galeri(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }
}
