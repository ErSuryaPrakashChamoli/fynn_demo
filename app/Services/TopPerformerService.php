<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Employee;
use Carbon\Carbon;

class TopPerformerService
{
    public function getTopPerformer(): array
    {
        $employees = Employee::where('designation', '1')->get();
     
        $performers = [];

        foreach ($employees as $employee) {

            $target = is_numeric($employee->category)
                ? (float) $employee->category
                : 2500000;

            $achievement = Customer::where('employee_id', $employee->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('sanctioned_loan_amount');

            $cashback = Customer::where('employee_id', $employee->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('cashback');

            $subvention = Customer::where('employee_id', $employee->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('subvention');

            $docking = Customer::where('employee_id', $employee->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('docking');

            $countAchievement = $achievement - ((($cashback + $subvention + $docking) / 2) * 100);

            $percentage = $target > 0
                ? round(($countAchievement / $target) * 100, 2)
                : 0;

            $performers[] = [
                'name' => $employee->emp_name ?? $employee->name,
                'disbursal' => $achievement,
                'percentage' => $percentage,
            ];
        }

        usort($performers, function ($a, $b) {
            return $b['percentage'] <=> $a['percentage'];
        });

        return array_slice($performers, 0, 5);
    }
}