<?php

namespace App\Filament\Resources\FollowUps\Schemas;

use Filament\Schemas\Schema;


use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;

class FollowUpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //

                Section::make('Customer Details')
                    ->schema([

                                TextInput::make('customer_name')
                                    ->label('Customer Name')
                                    ->content(fn ($record) => $record?->customer?->customer_name),

                                TextInput::make('mobile_no')
                                    ->label('Phone')
                                    ->content(fn ($record) => $record?->customer?->mobile_no),

                                TextInput::make('pan_number')
                                    ->label('PAN')
                                    ->content(fn ($record) => $record?->customer?->pan_number),

                                TextInput::make('current_location')
                                    ->label('Current Location')
                                    ->content(fn ($record) => $record?->customer?->current_location),

                                TextInput::make('job_location')
                                    ->label('Job Location')
                                    ->content(fn ($record) => $record?->customer?->job_location),

                                TextInput::make('salary')
                                    ->label('Salary')
                                    ->content(fn ($record) => "₹".number_format($record?->customer?->salary)),
                          ]),

                          Section::make('Follow Up')

                                ->schema([

                                    DatePicker::make('follow_up_date')
                                        ->required()
                                        ->default(now()),

                                    Select::make('follow_up_type')
                                        ->options([
                                            'Call'=>'Call',
                                            'WhatsApp'=>'WhatsApp',
                                            'Email'=>'Email',
                                            'Visit'=>'Visit',
                                        ])
                                        ->required(),

                                    Select::make('status')
                                        ->options([
                                            'Pending'=>'Pending',
                                            'Interested'=>'Interested',
                                            'Not Interested'=>'Not Interested',
                                            'Busy'=>'Busy',
                                            'No Response'=>'No Response',
                                        ])
                                        ->required(),

                                    DatePicker::make('next_follow_up_date'),

                                    Textarea::make('remarks')
                                        ->rows(5)
                                        ->required(),

                                ])

                          
                    ->columns(2)


                    


                ]);
    }
}
