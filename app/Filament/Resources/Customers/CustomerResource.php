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
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;


use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;

use Filament\Forms\Components\FileUpload;


use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;

use Filament\Facades\Filament;






class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'customer_name';

    public static function form(Schema $schema): Schema
      {
        return $schema
            ->columns(2)
            ->schema([
                // Section::make('Customer Loan Journey')
                //     ->schema([
                //         View::make('filament.components.customer-journey-progress')
                //             ->key('customerJourneyProgress'),
                //     ])
                //     ->columnSpanFull(),

                View::make('filament.components.customer-journey-progress')
                    ->key('customerJourneyProgress')
                    ->columnSpanFull()
                    ->visibleOn('edit') // Only shows on Edit page
                    ->extraAttributes([
                        // 'self-start' is the magic class that makes sticky work in CSS Grid
                        'class' => 'sticky z-50 self-start',
                        // Force the top margin so it sits neatly under the Filament navbar
                        'style' => 'top: 5.5rem;', 
                    ]),

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
                            ->label('Salary')
                            ->prefix('₹')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateHydrated(function ($component, $state) {
                                if (filled($state)) {
                                    $component->state(number_format((float) $state, 0, '.', ','));
                                }
                            })
                            ->dehydrateStateUsing(fn($state) => filled($state) ? str_replace(',', '', $state) : null)
                            ->formatStateUsing(fn($state) => filled($state) ? number_format((float) str_replace(',', '', $state), 0, '.', ',') : null),

                        TextInput::make('current_location')
                            ->label('Current Location')
                            ->maxLength(255),

                        Select::make('eligibility_status')
                            ->label('Eligibility')
                            ->options([
                                'eligible' => 'Eligible',
                                'not_eligible' => 'Not Eligible',
                            ])
                            ->live()
                            ->disabled(fn (): bool => ! auth()->check() || ! auth()->user()?->hasAnyRole(['Admin', 'Manager'])),

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
                            ->visible(fn(Get $get): bool => $get('eligibility_status') === 'not_eligible')
                            ->required(fn(Get $get): bool => $get('eligibility_status') === 'not_eligible'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Journey')
                    ->schema([
                        Select::make('company_category')
                            ->label('Company Category')
                            ->options([
                                'private_limited' => 'Private Limited',
                                'public_limited' => 'Public Limited',
                                'mnc' => 'MNC',
                                'government' => 'Government',
                                'semi_government' => 'Semi Government',
                                'psu' => 'PSU',
                                'proprietorship' => 'Proprietorship',
                                'partnership' => 'Partnership',
                                'llp' => 'LLP',
                                'startup' => 'Startup',
                                'self_employed' => 'Self Employed',
                            ])
                            ->searchable()
                            ->preload(),

                        Select::make('loan_applied')
                            ->label('Loan Applied For')
                            ->options([
                                'personal_loan' => 'Personal Loan',
                                'business_loan' => 'Business Loan',
                                'home_loan' => 'Home Loan',
                                'car_loan' => 'Car Loan',
                                'education_loan' => 'Education Loan',
                                'gold_loan' => 'Gold Loan',
                                'lap' => 'Loan Against Property',
                                'credit_card' => 'Credit Card',
                                'overdraft' => 'Overdraft',
                                'other' => 'Other',
                            ])
                            ->searchable()
                            ->preload()
                            ->live(),

                        TextInput::make('other_loan_applied')
                            ->label('Other Loan Type')
                            ->visible(fn(Get $get): bool => $get('loan_applied') === 'other')
                            ->required(fn(Get $get): bool => $get('loan_applied') === 'other')
                            ->maxLength(255),

                        Select::make('bank_eligible_for')
                            ->label('Bank Eligible For')
                            ->options([
                                'HDFC Bank' => 'HDFC Bank',
                                'ICICI Bank' => 'ICICI Bank',
                                'Axis Bank' => 'Axis Bank',
                                'State Bank of India' => 'State Bank of India',
                                'Kotak Mahindra Bank' => 'Kotak Mahindra Bank',
                                'IndusInd Bank' => 'IndusInd Bank',
                                'Yes Bank' => 'Yes Bank',
                                'Punjab National Bank' => 'Punjab National Bank',
                                'Bank of Baroda' => 'Bank of Baroda',
                                'Canara Bank' => 'Canara Bank',
                                'IDFC First Bank' => 'IDFC First Bank',
                                'AU Small Finance Bank' => 'AU Small Finance Bank',
                                'Other' => 'Other',
                            ])
                            ->searchable()
                            ->preload()
                            ->live(),

                        TextInput::make('other_bank_eligible_for')
                            ->label('Other Bank Name')
                            ->maxLength(255)
                            ->visible(fn(Get $get): bool => $get('bank_eligible_for') === 'Other')
                            ->required(fn(Get $get): bool => $get('bank_eligible_for') === 'Other'),

                        Select::make('journey_status')
                            ->label('Journey')
                            ->options([
                                'sfl' => 'SFL',
                                'underwriting' => 'Underwriting',
                                'approved' => 'Approved',
                                'not_approved' => 'Not Approved',
                                'sanctioned' => 'Sanctioned',
                            ])
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
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'not_approved')
                            ->required(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'not_approved'),

                        // Added Not Approved Remarks in the Journey section so it stays visible during rejection
                        Textarea::make('not_approved_remarks')
                            ->label('Rejection Remarks')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'not_approved'),
                    ])
                    ->columns(2)
                    ->columnSpan(1)
                    ->visible(fn (): bool => auth()->check() && auth()->user()?->hasAnyRole(['Admin', 'Manager'])),

                Section::make('Sanctioned Details')
                    ->schema([
                        TextInput::make('channel')
                            ->label('Channel')
                            ->maxLength(255)
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'sanctioned'),
                            
                        TextInput::make('application_no')
                            ->label('Application No')
                            ->maxLength(255),

                        TextInput::make('lan_no')
                            ->label('LAN No')
                            ->maxLength(255),

                        TextInput::make('sanctioned_bank')
                            ->label('Bank Name')
                            ->maxLength(255),

                        TextInput::make('sanctioned_loan_amount')
                            ->label('Loan Amount')
                            ->numeric(),

                        Select::make('documentation_status')
                            ->label('Documentation')
                            ->options([
                                'complete' => 'Complete',
                                'pending' => 'Pending',
                            ])
                            ->live(),

                        Select::make('pending_document')
                            ->label('Pending Document')
                            ->options([
                                'aadhaar_card' => 'Aadhaar Card',
                                'pan_card' => 'PAN Card',
                                'salary_slip' => 'Salary Slip',
                                'bank_statement' => 'Bank Statement',
                                'itr' => 'ITR',
                                'photo' => 'Photo',
                                'office_id_card' => 'Office ID Card',
                                'residence_proof' => 'Residence Proof',
                                'other' => 'Other',
                            ])
                            ->visible(fn(Get $get): bool => strtolower((string) $get('documentation_status')) === 'pending'),

                        Select::make('underwriting_status')
                            ->label('Underwriting Status')
                            ->options([
                                'in_process' => 'In Process',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'hold' => 'Hold',
                            ])
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'underwriting'),

                        TextInput::make('approved_loan_amount')
                            ->label('Approved Loan Amount')
                            ->numeric()
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'approved'),

                        // Separated Remarks Fields based on Journey Status
                        Textarea::make('sfl_remarks')
                            ->label('SFL Remarks')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'sfl'),

                        Textarea::make('underwriting_remarks')
                            ->label('Underwriting Remarks')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'underwriting'),

                        Textarea::make('approved_remarks')
                            ->label('Approved Remarks')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'approved'),

                        Textarea::make('sanctioned_remarks')
                            ->label('Sanctioned Remarks')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'sanctioned'),
                    ])
                    ->columns(2)
                    ->columnSpan(1)
                    ->visible(fn(Get $get): bool => in_array(strtolower((string) $get('journey_status')), ['sfl', 'underwriting', 'approved', 'sanctioned'])),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application_no')
                    ->label('Application No')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lan_no')
                    ->label('LAN No')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sanctioned_loan_amount')
                    ->label('Loan Amount')
                    ->formatStateUsing(fn($state) => filled($state) ? '₹' . number_format((float) $state, 0) : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mobile_no')
                    ->label('Mobile No')
                    ->formatStateUsing(function (?string $state): string {
                        if (blank($state) || strlen($state) < 4) {
                            return $state ?? '-';
                        }

                        return substr($state, 0, 4) . 'XXXXXX';
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('loan_applied')
                    ->label('Loan Applied')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('salary')
                    ->label('Salary')
                    ->formatStateUsing(fn($state) => filled($state) ? '₹' . number_format((float) $state, 0) : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('eligibility_status')
                    ->label('Eligibility')
                    ->badge(),

                Tables\Columns\TextColumn::make('bank_eligible_for')
                    ->label('Bank Eligible For')
                    ->formatStateUsing(function ($state, $record) {
                        return strtolower((string) $state) === 'other'
                            ? ($record->other_bank_eligible_for ?: '-')
                            : $state;
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('journey_status')
                    ->label('Journey')
                    ->badge(),

                Tables\Columns\TextColumn::make('sanctioned_bank')
                    ->label('Bank')
                    ->searchable(),

                Tables\Columns\TextColumn::make('channel')
                    ->label('Channel')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
               DeleteBulkAction::make(),
            ]);
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
