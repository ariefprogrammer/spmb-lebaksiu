<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total = Pendaftar::count();
        $lakiLaki = Pendaftar::where('jenis_kelamin', 'laki-laki')->count();
        $perempuan = Pendaftar::where('jenis_kelamin', 'perempuan')->count();
        $daftarUlang = Pendaftar::whereNotNull('daftar_ulang_at')->count();

        return [
            Stat::make('Total Pendaftar', number_format($total, 0, ',', '.'))
                ->icon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Laki-laki', number_format($lakiLaki, 0, ',', '.'))
                ->icon('heroicon-o-user')
                ->color('info'),

            Stat::make('Perempuan', number_format($perempuan, 0, ',', '.'))
                ->icon('heroicon-o-user')
                ->color('warning'),

            Stat::make('Sudah Daftar Ulang', number_format($daftarUlang, 0, ',', '.'))
                ->description($total > 0 ? round(($daftarUlang / $total) * 100) . '% dari total pendaftar' : null)
                ->icon('heroicon-o-clipboard-document-check')
                ->color('success'),
        ];
    }
}