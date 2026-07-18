<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class DailyCommitmentStats extends StatsOverviewWidget
{

protected static ?int $sort = 3;


    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {

            $customersQuery = Customer::query()
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);

        } else {

            $employeeId = $user->employee_id;

            if (! $employeeId) {
                return [
                    Stat::make('No Employee Assigned', 'N/A')
                        ->description('Please contact Administrator')
                        ->color('danger'),
                ];
            }

            $customersQuery = Customer::where('employee_id', $employeeId)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        $eligible = (clone $customersQuery)
            ->where('eligibility_status', 'eligible')
            ->count();

        $totalCustomers = (clone $customersQuery)->count();

        $sanctioned = (clone $customersQuery)
            ->whereNotNull('sanctioned_loan_amount')
            ->count();

        $documentationPending = (clone $customersQuery)
            ->where('documentation_status', 'pending')
            ->count();

        return [
            Stat::make('Eligible OTP', $eligible)
                ->description($user->hasRole('Admin') ? 'Company Eligible Loans' : 'Eligible for Loan')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('No of OTPS', $totalCustomers)
                ->description($user->hasRole('Admin') ? 'Total Applications' : 'Your Active Applications')
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Login', $sanctioned)
                ->description($user->hasRole('Admin') ? 'Total Sanctioned Cases' : 'Sanctioned Cases')
                ->color('warning')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Documentation Pending', $documentationPending)
                ->description($user->hasRole('Admin') ? 'Company Pending Documents' : 'Pending Documents')
                ->color('danger')
                ->icon('heroicon-o-document-text'),
        ];
    }
}
