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


        switch ($data['eligibility_status']) {

                case 'eligible':
                    $data['journey_status'] = 'sfl';
                    break;

                case 'consent_pending':
                    $data['journey_status'] = 'not_started';
                    break;

                case 'not_eligible':
                    $data['journey_status'] = 'not_started';
                    break;
            }

        // $data['journey_status'] = 'sfl';


            return $data;
        }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
