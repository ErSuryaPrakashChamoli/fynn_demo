<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;

class HierarchyService
{
    public static function visibleEmployeeIds(User $user): array
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Admin')) {
            return Employee::pluck('id')->toArray();
        }

        $employee = $user->employee;

        if (! $employee) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | CLUSTER HEAD
        |--------------------------------------------------------------------------
        */

       if ($employee->designation === 'Cluster Manager') {

            return Employee::where('cluster_id', $employee->id)
                ->orWhere('id', $employee->id)
                ->pluck('id')
                ->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        if ($employee->designation === 'Manager') {

            return Employee::where('manager_id', $employee->id)
                ->orWhere('id', $employee->id)
                ->pluck('id')
                ->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | TEAM LEADER
        |--------------------------------------------------------------------------
        */

        if ($employee->designation === 'Team Leader') {

            return Employee::where('superviser_id', $employee->id)
                ->orWhere('id', $employee->id)
                ->pluck('id')
                ->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | CALLER
        |--------------------------------------------------------------------------
        */

        return [$employee->id];
    }
}