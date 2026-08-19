<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'nama',
        'jurusan_id',
        'tahun_lulus',
        'tempat_kerja',
        'foto',
        'is_featured',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'tahun_lulus' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }
}
