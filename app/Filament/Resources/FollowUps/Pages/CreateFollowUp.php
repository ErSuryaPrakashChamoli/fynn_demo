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
               

                // $data['customer_id'] = request()->query('customer_id');
                

                // dd($data);

                // $data['customer_id'] = request()->integer('customer');


                // dd($user->employee_id);
                // dd(request()->user()->employee_id);
                // dd($data);
                // dd(auth()->user()->employee_id);
                // dd(auth()->user()?->employee_id);

                // if (auth()->check()) {
                //     dd(auth()->user()->employee_id);
                // } else {
                //     dd('No user is currently logged in.');
                // }
                // $data['employee_id'] = auth()->user()->employee_id;
                // $data['customer_id'] = request()->integer('customer');
// dd($data);
                return $data;
            }
}
