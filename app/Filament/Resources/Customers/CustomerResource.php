<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;

use Filament\Schemas\Components\Section;


use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;


class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'customer_name';

    public static function form(Schema $schema): Schema
    {

             return $schema->schema([
            Section::make('Customer Basic Details')
                ->schema([
                    TextInput::make('customer_name')
                        ->label('Customer Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('mobile_no')
                        ->label('Mobile No')
                        ->tel()
                        ->required()
                        ->maxLength(20),

                    TextInput::make('email')
                        ->email()
                        ->maxLength(255),

                    TextInput::make('pan_number')
                        ->label('PAN Number')
                        ->maxLength(20),

                    TextInput::make('job_location')
                        ->label('Job Location')
                        ->maxLength(255),

                    TextInput::make('residence_location')
                        ->label('Residence Location')
                        ->maxLength(255),

                    TextInput::make('salary')
                        ->numeric()
                        ->label('Salary'),

                    TextInput::make('current_location')
                        ->label('Current Location')
                        ->maxLength(255),

                    TextInput::make('company_category')
                        ->label('Company Category')
                        ->maxLength(255),

                    TextInput::make('bank_eligible_for')
                        ->label('Bank Eligible For')
                        ->maxLength(255),

                    TextInput::make('loan_applied')
                        ->label('Loan Applied')
                        ->maxLength(255),
                ])
                ->columns(2),

            Section::make('Eligibility')
                ->schema([
                    Select::make('eligibility_status')
                        ->label('Eligible / Not Eligible')
                        ->options([
                            'eligible' => 'Eligible',
                            'not_eligible' => 'Not Eligible',
                        ])
                        ->required()
                        ->live(),

                    Select::make('eligibility_reason')
                        ->label('Not Eligible Reason')
                        ->options([
                            'company_not_listed' => 'Company Not Listed',
                            'cibil_score' => 'CIBIL Score',
                            'defaulter_bounces' => 'Defaulter / Bounces',
                            'no_residence_proof' => 'No Residence Proof',
                            'low_salary' => 'Low Salary',
                            'location_issue' => 'Location',
                        ])
                        ->visible(fn (Get $get): bool => $get('eligibility_status') === 'not_eligible')
                        ->required(fn (Get $get): bool => $get('eligibility_status') === 'not_eligible'),
                ])
                ->columns(2),

            Section::make('Journey')
                ->schema([
                    Select::make('journey_status')
                        ->label('Journey')
                        ->options([
                            'sfl' => 'SFL',
                            'underwriting' => 'Underwriting',
                            'approved' => 'Approved',
                            'not_approved' => 'Not Approved',
                            'sanctioned' => 'Sanctioned',
                        ])
                        ->required()
                        ->live(),

                    Select::make('journey_not_approved_reason')
                        ->label('Not Approved Reason')
                        ->options([
                            'cibil_score' => 'CIBIL Score',
                            'defaulter_bounces' => 'Defaulter / Bounces',
                            'no_residence_proof' => 'No Residence Proof',
                            'low_salary' => 'Low Salary',
                            'location_issue' => 'Location',
                        ])
                        ->visible(fn (Get $get): bool => $get('journey_status') === 'not_approved')
                        ->required(fn (Get $get): bool => $get('journey_status') === 'not_approved'),
                ])
                ->columns(2),

            Section::make('Sanctioned Details')
                ->schema([
                    TextInput::make('sanctioned_bank')
                        ->label('Bank')
                        ->maxLength(255),

                    TextInput::make('sanctioned_loan_amount')
                        ->label('Loan Amount')
                        ->numeric(),

                    TextInput::make('cashback')
                        ->numeric()
                        ->label('Cashback'),

                    TextInput::make('subvention')
                        ->numeric()
                        ->label('Subvention'),

                    TextInput::make('payout_rate')
                        ->numeric()
                        ->label('Payout Rate'),

                    Textarea::make('bank_condition')
                        ->label('Bank Condition')
                        ->rows(3),

                    Select::make('attachment_required')
                        ->label('Attachment Required')
                        ->options([
                            'yes' => 'Yes',
                            'no' => 'No',
                        ]),
                ])
                ->visible(fn (Get $get): bool => $get('journey_status') === 'sanctioned')
                ->columns(2),
        ]);

        // return CustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mobile_no')
                    ->label('Mobile No')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('eligibility_status')
                    ->label('Eligibility')
                    ->badge(),

                Tables\Columns\TextColumn::make('journey_status')
                    ->label('Journey')
                    ->badge(),

                Tables\Columns\TextColumn::make('sanctioned_bank')
                    ->label('Bank'),

                Tables\Columns\TextColumn::make('sanctioned_loan_amount')
                    ->label('Loan Amount'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
            
        // return CustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
