<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
        {
                     
            
        $user = auth()->user();
        $data['employee_id'] = $user->employee_id;
     

            return $data;
        }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
