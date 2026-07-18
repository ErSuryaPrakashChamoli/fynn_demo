<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Employee;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

   protected function getHeaderActions(): array
{
    $employee = auth()->user()->employee;

    $actions = [];

    if (
        $employee?->designation !== Employee::DESIGNATION_CALLER &&
        ! $this->record->documents_submitted
    ) {
        $actions[] = EditAction::make();
    }

    return $actions;
}

}
