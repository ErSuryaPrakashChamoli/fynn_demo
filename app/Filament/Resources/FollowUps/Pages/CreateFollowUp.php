<?php

namespace App\Filament\Resources\FollowUps\Pages;

use App\Filament\Resources\FollowUps\FollowUpResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFollowUp extends CreateRecord
{
    protected static string $resource = FollowUpResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
        {
        $data['employee_id'] = auth()->user()->employee_id;

        return $data;
        }
}
