<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Analytics';
    protected function getStats(): array
    {
        $date = Carbon::now()->subDays(14)->startOfDay();

        $userLast14Days = User::where('created_at', '>=', $date)
            ->selectRaw('count(id) as count, date(created_at) as created_at')
            ->groupByRaw('date(created_at)')
            ->orderByRaw('date(created_at) asc')
            ->get();

        return [
            Stat::make('Users', User::count())
                ->icon('heroicon-o-user')
                ->chart($userLast14Days->pluck('count')->toArray())
                ->description($userLast14Days->sum('count') . " Increase in the last 14 days")
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
        ];
    }
}
