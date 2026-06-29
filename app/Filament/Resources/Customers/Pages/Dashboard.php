<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\Page;

class Dashboard extends Page
{
    protected static string $resource = CustomerResource::class;

    protected string $view = 'filament.resources.customers.pages.dashboard';
}
