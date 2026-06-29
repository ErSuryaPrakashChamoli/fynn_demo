<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Customer;
use App\Models\User;



class TargetStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        $target = 2500000;

        $achievement = Customer::whereMonth('created_at', now()->month)
        ->sum('sanctioned_loan_amount');

        $pending = max(0, $target - $achievement);

        $remainingDays = max(1, now()->daysInMonth - now()->day);

        $drr = $pending / $remainingDays;


                return [
            Stat::make('🎯 Target', '₹ ' . number_format($target))
                ->description('Monthly Target')
                ->color('primary'),

            Stat::make('💰 Achievement', '₹ ' . number_format($achievement))
                ->description('Current Month')
                ->color('success'),

            Stat::make('⏳ Pending', '₹ ' . number_format($pending))
                ->description('Remaining Target')
                ->color('warning'),

            Stat::make('📈 DRR', '₹ ' . number_format($drr))
                ->description('Daily Required Run Rate')
                ->color('danger'),
        ];

       
    }
}
