<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Customer;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use NumberFormatter;



class TargetStats extends StatsOverviewWidget
{

    protected static ?int $sort = 1;

    protected function getStats(): array
    {


        $login_user = auth()->user();
        $employee = Employee::where('id', $login_user->employee_id)->first();
        
        $target = 0;
        $achievement = 0;
        $totalCashback = 0;
        $totalSubvention = 0;
        
        $targetLevel = '🥈 Target Calculation';
        $targetColor = 'info';

        if ($employee) {
            $designation = $employee->designation; // '1' = Caller, '2' = TL, '3' = Manager, '4' = Cluster

            if ($designation === '1') {
                // --- 1. CALLER LOGIC ---
                $target = is_numeric($employee->category) ? (float) $employee->category : 2500000;
                
                $achievement = Customer::where('employee_id', $employee->id)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('sanctioned_loan_amount');

                $totalCashback = Customer::where('employee_id', $employee->id)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('cashback');

                $totalSubvention = Customer::where('employee_id', $employee->id)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('subvention');
                
                if ($target >= 3500000) {
                    $targetLevel = '💎 Diamond Target';
                    $targetColor = 'success';
                } elseif ($target >= 3000000) {
                    $targetLevel = '🥇 Gold Target';
                    $targetColor = 'warning';
                } else {
                    $targetLevel = '🥈 Silver Target';
                    $targetColor = 'info';
                }

            } elseif ($designation === '2') {
                // --- 2. TEAM LEADER LOGIC ---
                $callerIds = Employee::where('superviser_id', $employee->id)->pluck('id')->toArray();
                $callerCount = count($callerIds);

                // अगर डेटाबेस में कैटेगरी खाली या स्ट्रिंग है, तो डिफ़ॉल्ट 25 लाख केवल कॉलर्स ('1') के लिए मानें
                $baseTarget = Employee::whereIn('id', $callerIds)
                    ->where('designation', '1')
                    ->get()
                    ->sum(fn($emp) => is_numeric($emp->category) ? (float)$emp->category : 2500000);

                if ($callerCount < 3) {
                    $target = $baseTarget + 3000000;
                    $targetLevel = "👥 TL (< 3 Callers: +₹30L Penalty Included)";
                    $targetColor = 'danger';
                } else {
                    $target = $baseTarget;
                    $targetLevel = '👥 Team Leader (Callers Sum)';
                    $targetColor = 'primary';
                }

                $achievement = Customer::whereIn('employee_id', $callerIds)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('sanctioned_loan_amount');

                $totalCashback = Customer::whereIn('employee_id', $callerIds)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('cashback');

                $totalSubvention = Customer::whereIn('employee_id', $callerIds)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('subvention');

            } elseif ($designation === '3') {
                // --- 3. MANAGER LOGIC (STRICT HIERARCHY FIX) ---
                $teamLeaderIds = Employee::where('manager_id', $employee->id)
                    ->where('designation', '2')
                    ->pluck('id')
                    ->toArray();

                // केवल वो कॉलर्स जो इन टीम लीडर्स के अंतर्गत काम कर रहे हैं
                $callerIds = [];
                if (!empty($teamLeaderIds)) {
                    $callerIds = Employee::whereIn('superviser_id', $teamLeaderIds)
                        ->where('designation', '1')
                        ->pluck('id')
                        ->toArray();
                }

                // वो डायरेक्ट कॉलर्स जो बिना किसी TL के सीधे इस मैनेजर को रिपोर्ट कर रहे हैं
                $directCallers = Employee::where('manager_id', $employee->id)
                    ->where('designation', '1')
                    ->pluck('id')
                    ->toArray();

                // टारगेट और अचीवमेंट के लिए केवल एक्चुअल एक्टिव कॉलर्स की आईडी मर्ज करें (टीएल आईडी को बाहर रखें)
                $allActiveCallers = array_unique(array_merge($callerIds, $directCallers));
                $allSubordinateIds = array_unique(array_merge($teamLeaderIds, $allActiveCallers));

                if (!empty($allActiveCallers)) {
                    // यहाँ डिफ़ॉल्ट वैल्यू केवल वास्तविक कॉलर्स के लिए ही ट्रिगर होगी
                    $target = Employee::whereIn('id', $allActiveCallers)
                        ->get()
                        ->sum(fn($emp) => is_numeric($emp->category) ? (float)$emp->category : 2500000);
                } else {
                    $target = 0;
                }

                // प्रत्येक शॉर्ट-टीम टीएल के लिए मैनेजर के कुल टारगेट में ₹30 लाख जोड़ें
                foreach ($teamLeaderIds as $tlId) {
                    $tlCallerCount = Employee::where('superviser_id', $tlId)->where('designation', '1')->count();
                    if ($tlCallerCount < 3) {
                        $target += 3000000; 
                    }
                }

                if (!empty($allSubordinateIds)) {
                    $achievement = Customer::whereIn('employee_id', $allSubordinateIds)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('sanctioned_loan_amount');

                    $totalCashback = Customer::whereIn('employee_id', $allSubordinateIds)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('cashback');

                    $totalSubvention = Customer::whereIn('employee_id', $allSubordinateIds)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('subvention');
                }

                $targetLevel = '👔 Manager (Subordinates + TL Penalties)';
                $targetColor = 'warning';

            } elseif ($designation === '4') {
                // --- 4. CLUSTER MANAGER LOGIC ---
                $managerIds = Employee::where('cluster_id', $employee->id)->where('designation', '3')->pluck('id')->toArray();
                $teamLeaderIds = [];
                if (!empty($managerIds)) {
                    $teamLeaderIds = Employee::whereIn('manager_id', $managerIds)->where('designation', '2')->pluck('id')->toArray();
                }
                
                $callerIds = [];
                if (!empty($teamLeaderIds)) {
                    $callerIds = Employee::whereIn('superviser_id', $teamLeaderIds)->where('designation', '1')->pluck('id')->toArray();
                }

                $allTeamIds = array_merge($managerIds, $teamLeaderIds, $callerIds);

                if (!empty($callerIds)) {
                    $target = Employee::whereIn('id', $callerIds)
                        ->get()
                        ->sum(fn($emp) => is_numeric($emp->category) ? (float)$emp->category : 2500000);
                } else {
                    $target = 0;
                }

                foreach ($teamLeaderIds as $tlId) {
                    $tlCallerCount = Employee::where('superviser_id', $tlId)->where('designation', '1')->count();
                    if ($tlCallerCount < 3) {
                        $target += 3000000; 
                    }
                }

                if (!empty($allTeamIds)) {
                    $achievement = Customer::whereIn('employee_id', $allTeamIds)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('sanctioned_loan_amount');

                    $totalCashback = Customer::whereIn('employee_id', $allTeamIds)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('cashback');

                    $totalSubvention = Customer::whereIn('employee_id', $allTeamIds)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year)
                        ->sum('subvention');
                }

                $targetLevel = '🏢 Cluster Manager (Total Hierarchy Sum)';
                $targetColor = 'danger';
            }
        }

        $countAchievement = $achievement - ((($totalCashback + $totalSubvention) / 2) * 100);
        $pending = max(0, $target - $countAchievement);
        $remainingDays = max(1, Carbon::now()->daysInMonth - Carbon::now()->day);
        $drr = $pending / $remainingDays;

        $indianCurrencyFormatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY); 
        $indianCurrencyFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);

        return [
            Stat::make('🎯 Target', $indianCurrencyFormatter->formatCurrency($target, 'INR'))
                ->description($targetLevel)
                ->color($targetColor),

            Stat::make('💰 Actual Achievement', $indianCurrencyFormatter->formatCurrency($achievement, 'INR'))
                ->description('Disbursed Loan Volume')
                ->color('success'),
             
            Stat::make('📊 Count Achievement', $indianCurrencyFormatter->formatCurrency($countAchievement, 'INR'))
                ->description('Adjusted Net Volume')
                ->color('primary'),

            Stat::make('⏳ Pending Target', $indianCurrencyFormatter->formatCurrency($pending, 'INR'))
                ->description('Remaining Target')
                ->color($pending > 0 ? 'warning' : 'success'),

            Stat::make('📈 DRR', $indianCurrencyFormatter->formatCurrency($drr, 'INR'))
                ->description('Daily Required Run Rate')
                ->color($drr > 0 ? 'danger' : 'success'),
        ];

    }
}
