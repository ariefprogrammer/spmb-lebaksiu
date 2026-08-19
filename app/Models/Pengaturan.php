<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Ambil satu nilai pengaturan berdasarkan key, dengan cache singkat
     * supaya tidak query berulang di halaman publik (footer, topbar, dll).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("pengaturan.$key", function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("pengaturan.$key");
    }
}
