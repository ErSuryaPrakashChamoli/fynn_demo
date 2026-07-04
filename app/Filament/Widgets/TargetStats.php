<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Customer;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Carbon;



class TargetStats extends StatsOverviewWidget
{

    protected static ?int $sort = 1;
    protected function getStats(): array
    {

    $login_user = auth()->user();
    $target = Employee::where('id', $login_user->employee_id)->first()?->category ?? 2500000;
    $achievement = Customer::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('sanctioned_loan_amount');

    if ($target >= 3500000) {
        $targetLevel = '💎 Platinum Target (₹35 Lakh)';
        $targetColor = 'success';
    } elseif ($target >= 3000000) {
        $targetLevel = '🥇 Gold Target (₹30 Lakh)';
        $targetColor = 'warning';
    } else {
        $targetLevel = '🥈 Silver Target (₹25 Lakh)';
        $targetColor = 'info';
    }

    $pending = max(0, $target - $achievement);
        $remainingDays = max(1, Carbon::now()->daysInMonth - Carbon::now()->day);
        $drr = $pending / $remainingDays;
    // $target = Employee::where('id', $login_user_id->employee_id)->first()?->category ?? 2500000;


    // dd($login_user_id);
    // $employee = auth()->user()?->employee;


    // $employee = \App\Models\Employee::where('emp_id', auth()->user()->employee_id)->first();
    
    // dd($employee);
    // $target = $employee?->category ?? 2500000;

    $target = $target ?? 2500000;

    // dd($target);


    $targetLevel = '🥈 Silver Target (₹25 Lakh)';
    $targetColor = 'gray';

    $achievement = Customer::whereMonth('created_at', now()->month)
        ->sum('sanctioned_loan_amount');

    if ($target >= 3500000) {
        $targetLevel = '💎 Platinum Target (₹35 Lakh)';
        $targetColor = 'success';
    } elseif ($target >= 3000000) {
        $targetLevel = '🥇 Gold Target (₹30 Lakh)';
        $targetColor = 'warning';
    } else {
        $targetLevel = '🥈 Silver Target (₹25 Lakh)';
        $targetColor = 'info';
    }


    $pending = max(0, $target - $achievement);


    $remainingDays = max(1, now()->daysInMonth - now()->day);

    $drr = $pending / $remainingDays;


       return [
            Stat::make('🎯 Target', '₹ ' . number_format($target))
                ->description($targetLevel)
                ->color($targetColor),

            Stat::make('💰 Achievement', '₹ ' . number_format($achievement))
                ->description('Current Month')
                ->color('success'),

            Stat::make('⏳ Pending Target', '₹ ' . number_format($pending))
                ->description('Remaining Target')
                ->color($pending > 0 ? 'warning' : 'success'),

            Stat::make('📈 DRR', '₹ ' . number_format($drr))
                ->description('Daily Required  Rate')
                ->color($drr > 0 ? 'danger' : 'success'),
        ];


 
    // $target = $target;
    $badge = '🥈 Silver';
    $badgeDescription = 'Target: ₹25 Lakh';
    $badgeColor = 'gray';


    // if ($achievement >= 3500000) {
    //     $target = 3500000;
    //     $targetLevel = '💎 Platinum Target (₹35 Lakh)';
    //     $targetColor = 'success';
    // } elseif ($achievement >= 3000000) {
    //     $target = 3000000;
    //     $targetLevel = '🥇 Gold Target (₹30 Lakh)';
    //     $targetColor = 'warning';
    // } else {
    //     $target = 2500000;
    //     $targetLevel = '🥈 Silver Target (₹25 Lakh)';
    //     $targetColor = 'info';
    // }


 
    //   return [

    // Stat::make('🎯 Target', '₹ ' . number_format($target))
    // ->description($targetLevel)
    // ->color($targetColor),

    //     Stat::make('💰 Achievement', '₹ ' . number_format($achievement))
    //         ->description('Current Month')
    //         ->color('success'),

    //     Stat::make('⏳ Pending Target', '₹ ' . number_format($pending))
    //         ->description('Remaining Target')
    //         ->color('warning'),

    //     Stat::make('📈 DRR', '₹ ' . number_format($drr))
    //         ->description('Daily Required Run Rate')
    //         ->color('danger'),

     
    // ];
       
    }
}
