<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class DailyCommitmentStats extends StatsOverviewWidget
{

protected static ?int $sort = 2;


    protected function getStats(): array
    {

        $user = auth()->user();
        $employeeId = $user->employee_id;
    //    $employee = $user->employee_id;

       $employeeCustomersQuery = Customer::where('employee_id', $employeeId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);

        $eligible = (clone $employeeCustomersQuery)->where('eligibility_status', 'eligible')->count();
        $totalCustomers = (clone $employeeCustomersQuery)->count();

        $sanctioned = (clone $employeeCustomersQuery)->whereNotNull('sanctioned_loan_amount')->count();

        $documentationPending = (clone $employeeCustomersQuery)->where('documentation_status', 'pending')->count();

        return [
            Stat::make('Eligible OTP', $eligible)
                ->description('Eligible for Loan')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('No of OTPS', $totalCustomers)
                ->description('Your Active Applications')
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Login', $sanctioned)
                ->description('Sanctioned Cases')
                ->color('warning')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Documentation Pending', $documentationPending)
                ->description('Pending Documents')
                ->color('danger')
                ->icon('heroicon-o-document-text'),
        ];

       
       
    
        // $eligible = Customer::where('eligibility_status', 'eligible')->count();

        // $totalCustomers = Customer::count();

        // $sanctioned = Customer::whereNotNull('sanctioned_loan_amount')->count();

        // $documentationPending = Customer::where('documentation_status', 'pending')->count();

        //  return [
        //     Stat::make('Eligible OTP', $eligible)
        //         ->description('Eligible for Loan')
        //         ->color('success')
        //         ->icon('heroicon-o-check-circle'),

        //     Stat::make('No of OTPS', $totalCustomers)
        //         ->description('All Applications')
        //         ->color('primary')
        //         ->icon('heroicon-o-users'),

        //     Stat::make('Login', $sanctioned)
        //         ->description('Sanctioned Cases')
        //         ->color('warning')
        //         ->icon('heroicon-o-banknotes'),

        //     Stat::make('Documentation Pending', $documentationPending)
        //         ->description('Pending Documents')
        //         ->color('danger')
        //         ->icon('heroicon-o-document-text'),
        // ];
    }
}
