<?php

namespace App\Filament\Resources\FollowUps\Pages;

use App\Filament\Resources\FollowUps\FollowUpResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFollowUp extends CreateRecord
{
    protected static string $resource = FollowUpResource::class;

        protected function mutateFormDataBeforeCreate(array $data): array
            {
                // dd(auth()->id);

                $customerId = $data['customer_id']; 
                $user = auth()->user();
                $data['employee_id'] = $user->employee_id;
                $data['customer_id'] = $customerId;
               
                return $data;
            }
}
