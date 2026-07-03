<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Customer;
use App\Models\User;



class TargetStats extends StatsOverviewWidget
{

    protected static ?int $sort = 1;
    protected function getStats(): array
    {

    $target = 2500000;
    $targetLevel = '🥈 Silver Target (₹25 Lakh)';
    $targetColor = 'gray';

    $achievement = Customer::whereMonth('created_at', now()->month)
        ->sum('sanctioned_loan_amount');

    $pending = max(0, $target - $achievement);

    $remainingDays = max(1, now()->daysInMonth - now()->day);

    $drr = $pending / $remainingDays;

       $badge = '🥈 Silver';
    $badgeDescription = 'Target: ₹25 Lakh';
    $badgeColor = 'gray';


     if ($achievement >= 3500000) {
    $target = 3500000;
    $targetLevel = '💎 Platinum Target (₹35 Lakh)';
    $targetColor = 'success';
} elseif ($achievement >= 3000000) {
    $target = 3000000;
    $targetLevel = '🥇 Gold Target (₹30 Lakh)';
    $targetColor = 'warning';
} else {
    $target = 2500000;
    $targetLevel = '🥈 Silver Target (₹25 Lakh)';
    $targetColor = 'info';
}


      return [
      Stat::make('🎯 Target', '₹ ' . number_format($target))
    ->description($targetLevel)
    ->color($targetColor),

        Stat::make('💰 Achievement', '₹ ' . number_format($achievement))
            ->description('Current Month')
            ->color('success'),

        Stat::make('⏳ Pending Target', '₹ ' . number_format($pending))
            ->description('Remaining Target')
            ->color('warning'),

        Stat::make('📈 DRR', '₹ ' . number_format($drr))
            ->description('Daily Required Run Rate')
            ->color('danger'),

     
    ];
       
    }
}
