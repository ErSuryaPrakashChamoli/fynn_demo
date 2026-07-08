<?php

namespace App\Filament\Resources\Customers\Schemas;

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
use Filament\Schemas\Components\View;
use Filament\Forms\Components\CheckboxList;
use App\Models\City;
use Illuminate\Support\Str;


class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {

        $banks = [
            'BFL Prime' => 'BFL Prime',
            'BFL Growth' => 'BFL Growth',
            'BFL SOL' => 'BFL SOL',
            'BFL RSL' => 'BFL RSL',
            'ABFL' => 'ABFL',
            'Incred' => 'Incred',
            'Fibe' => 'Fibe',
            'Poonawala' => 'Poonawala',
            'Finnable' => 'Finnable',
            'Tata Capital' => 'Tata Capital',
            'Piramal Finance' => 'Piramal Finance',
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
        ];

        asort($banks);


        return $schema
            ->components([
                //
                View::make('filament.components.customer-journey-progress')
                    ->key('customerJourneyProgress')
                    ->columnSpanFull()
                    ->visibleOn('edit') 
                    ->extraAttributes([          
                        'class' => 'sticky z-50 self-start',
                        'style' => 'top: 5.5rem;', 
                    ]),

                Section::make('Customer Basic Details')
                    ->schema([
                       
                        TextInput::make('customer_name')
                        ->label('Customer Name')
                        ->required()
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('customer_name', Str::title($state));
                        }),                            

                     

                       
                        TextInput::make('mobile_no')
                        ->label('Mobile No')
                        ->tel()
                        ->required()
                        ->numeric()
                        ->minLength(10)
                        ->maxLength(10)
                        ->rule('digits:10')
                        ->placeholder('9876543210')
                        ->prefix('+91'),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),


                        TextInput::make('pan_number')
                            ->label('PAN Number')
                            ->required()
                            ->maxLength(10)
                            ->minLength(10)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('pan_number', strtoupper($state)))
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            ->rule('regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/')
                            ->validationMessages([
                                'regex' => 'Please enter a valid PAN number (e.g. ABCDE1234F).',
                            ])
                            ->placeholder('ABCDE1234F'),

                 

                    Select::make('job_location')
                    ->label('Job Location')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(fn () => City::query()
                        ->where('is_active', 1)
                        ->orderBy('city')
                        ->get()
                        ->pluck('city', 'city') 
                    ),


                    Select::make('residence_location')
                    ->label('Residence Location')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(fn () => City::query()
                        ->where('is_active', 1)
                        ->orderBy('city')
                        ->get()
                        ->mapWithKeys(fn ($item) => [
                            $item->city => "{$item->city}, {$item->state}" 
                        ])
                    ),

                        TextInput::make('salary')
                        ->label('Salary')
                        ->prefix('₹')
                        ->live()

                  
                        ->formatStateUsing(fn ($state) => filled($state)
                            ? indianCurrencyFormat($state)
                            : null)

                        ->afterStateUpdated(function ($state, callable $set) {
                            $value = preg_replace('/[^0-9]/', '', (string) $state);

                            if ($value !== '') {
                                $set('salary', indianCurrencyFormat($value));
                            }
                        })
                   
                        ->dehydrateStateUsing(fn ($state) => preg_replace('/[^0-9]/', '', (string) $state)),

                      

                    Select::make('current_location')
                    ->label('Current Location')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(fn () => City::query()
                        ->where('is_active', 1)
                        ->orderBy('city')
                        ->get()
                        ->mapWithKeys(fn ($item) => [
                            $item->city => "{$item->city}, {$item->state}" 
                        ])
                    ),

                        Select::make('eligibility_status')
                            ->label('Eligibility')
                              ->required()
                            ->options([
                                'eligible' => 'Eligible',
                                'not_eligible' => 'Not Eligible',
                            ])
                            ->live()
                            ->disabled(
                                fn (string $operation): bool =>
                                    $operation === 'edit' &&
                                    (! auth()->check() || ! auth()->user()->hasAnyRole(['Admin', 'Manager']))
                            ),

                    Select::make('assign_to')
                            ->label('Assign To')
                            ->relationship('assignedTo', 'emp_name')
                            ->searchable()
                            ->default(fn () => auth()->user()->employee?->id)
                            ->preload()
                            ->nullable(),

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
                    ->columnSpanFull()
                    ->disabled(fn (string $operation): bool =>
                    $operation === 'edit' &&
                    auth()->user()->hasRole('Employee')
                    ),

                Section::make('Journey')
                    ->schema([


                        TextInput::make('company_category')
                        ->label('Company Name')
                       
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('company_category', Str::title($state));
                        }),   
                            

                     

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
                             ->options($banks)
                            ->searchable()
                            ->preload()
                            ->live(),

                        TextInput::make('other_bank_eligible_for')
                            ->label('Other Bank Name')
                            ->maxLength(255)
                            ->visible(fn(Get $get): bool => $get('bank_eligible_for') === 'Other')
                            ->required(fn(Get $get): bool => $get('bank_eligible_for') === 'Other'),

                        Select::make('journey_status')
                            ->label('Application Status')
                            ->options([
                                'sfl' => 'SFL',
                                'underwriting' => 'Underwriting',
                                'approved' => 'Approved',
                                'not_approved' => 'Not Approved',
                                'sanctioned' => 'Disbursed',
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

                    
                        Textarea::make('not_approved_remarks')
                            ->label('Rejection Remarks')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn(Get $get): bool => strtolower((string) $get('journey_status')) === 'not_approved'),
                    ])
                    ->columns(2)
                    ->columnSpan(1)
                    ->visible(fn (): bool => auth()->check() && auth()->user()?->hasAnyRole(['Admin', 'Manager'])),

                Section::make('Application Details')
                    ->schema([

                        Select::make('channel')
                        ->label('Channel')
                        ->options([
                            'finance_buddha' => 'Finance Buddha',
                            'profin_care' => 'Profin Care',
                            'rare_crome' => 'Rare Crome',
                            'ruloans' => 'Ruloans',
                            'fast_credit' => 'Fast Credit',
                            'kms_finbud' => 'KMS Finbud',
                        ])
                        ->visible(fn (Get $get): bool => strtolower((string) $get('journey_status')) === 'sanctioned'),
                            
                        TextInput::make('application_no')
                            ->label('Application No')
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(255),

                        TextInput::make('lan_no')
                            ->label('LAN No')
                            ->maxLength(255),

                        Select::make('sanctioned_bank')
                            ->label('Bank Name')
                             ->options($banks) ,

                        TextInput::make('sanctioned_loan_amount')
                         ->label('Disbursed Loan Amount') 
                        ->prefix('₹')
                        ->live()

                  
                        ->formatStateUsing(fn ($state) => filled($state)
                            ? indianCurrencyFormat($state)
                            : null)

                        ->afterStateUpdated(function ($state, callable $set) {
                            $value = preg_replace('/[^0-9]/', '', (string) $state);

                            if ($value !== '') {
                                $set('sanctioned_loan_amount', indianCurrencyFormat($value));
                            }
                        })
                   
                        ->dehydrateStateUsing(fn ($state) => preg_replace('/[^0-9]/', '', (string) $state))
                        ->visible(fn (Get $get): bool => strtolower((string) $get('journey_status')) === 'sanctioned'),

                    TextInput::make('cashback')
                            ->label('Cashback') 
                            ->prefix('₹')
                            ->live()
                            ->formatStateUsing(fn ($state) => filled($state)
                                ? indianCurrencyFormat($state)
                                : null)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $value = preg_replace('/[^0-9]/', '', (string) $state);
                                    if ($value !== ''){
                                        $set('cashback', indianCurrencyFormat($value));
                                    }
                                })
                            ->dehydrateStateUsing(fn ($state) => preg_replace('/[^0-9]/', '', (string) $state))
                            ->visible(fn (Get $get): bool => strtolower((string) $get('journey_status')) === 'sanctioned'),

                     TextInput::make('subvention')
                         ->label('Subvention') 
                        ->prefix('₹')
                        ->live()

                  
                        ->formatStateUsing(fn ($state) => filled($state)
                            ? indianCurrencyFormat($state)
                            : null)

                        ->afterStateUpdated(function ($state, callable $set) {
                            $value = preg_replace('/[^0-9]/', '', (string) $state);

                            if ($value !== '') {
                                $set('subvention', indianCurrencyFormat($value));
                            }
                        })
                   
                        ->dehydrateStateUsing(fn ($state) => preg_replace('/[^0-9]/', '', (string) $state))
                        ->visible(fn (Get $get): bool => strtolower((string) $get('journey_status')) === 'sanctioned'),

                        TextInput::make('docking')
                            ->label('Docking') 
                            ->prefix('₹')
                            ->live()
                            ->formatStateUsing(fn ($state) => filled($state)
                                ? indianCurrencyFormat($state)
                                : null)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $value = preg_replace('/[^0-9]/', '', (string) $state);
                                    if ($value !== ''){
                                        $set('docking', indianCurrencyFormat($value));
                                    }
                                })
                            ->dehydrateStateUsing(fn ($state) => preg_replace('/[^0-9]/', '', (string) $state))
                            ->visible(fn (Get $get): bool => strtolower((string) $get('journey_status')) === 'sanctioned'),

                    
                           TextInput::make('eligible_loan_amount')
                                ->label('Eligible Loan Amount')
                                ->prefix('₹')
                                ->live()


                                ->formatStateUsing(fn ($state) => filled($state)
                                ? indianCurrencyFormat($state)
                                : null)

                                ->afterStateUpdated(function ($state, callable $set) {
                                $value = preg_replace('/[^0-9]/', '', (string) $state);

                                if ($value !== '') {
                                    $set('eligible_loan_amount', indianCurrencyFormat($value));
                                }
                                })

                                ->dehydrateStateUsing(fn ($state) => preg_replace('/[^0-9]/', '', (string) $state))
                                ->visible(fn (Get $get): bool => strtolower((string) $get('journey_status')) === 'sfl'),

                        Select::make('documentation_status')
                            ->label('Documentation')
                            ->options([
                                'complete' => 'Complete',
                                'pending' => 'Pending',
                            ])
                              ->visible(fn (Get $get): bool => strtolower((string) $get('journey_status')) === 'sfl')
                            ->live(),

        

                        CheckboxList::make('pending_document')
                            ->label('Pending Documents')
                            ->options([
                                'aadhaar_card'            => 'AADHAR Card',
                                'current_address_proof'   => 'Current Address Proof',
                                'electricity_bill'        => 'Electricity Bill',
                                'bank_statement'          => 'Bank Statement',
                                'form_26as'               => 'Form 26AS',
                                'photo'                   => 'Photo',
                                'payslip'                 => 'Payslip',
                                'soa_repayment_schedule'  => 'SOA / Repayment Schedule',
                                'other'            => 'Other',
                            ])
                            ->columns(2)
                            ->bulkToggleable()
                            ->searchable()
                            ->visible(fn (Get $get): bool => strtolower((string) $get('documentation_status')) === 'pending'),

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
                        ->prefix('₹')
                        ->live()


                        ->formatStateUsing(fn ($state) => filled($state)
                        ? indianCurrencyFormat($state)
                        : null)

                        ->afterStateUpdated(function ($state, callable $set) {
                        $value = preg_replace('/[^0-9]/', '', (string) $state);

                        if ($value !== '') {
                            $set('approved_loan_amount', indianCurrencyFormat($value));
                        }
                        })

                        ->dehydrateStateUsing(fn ($state) => preg_replace('/[^0-9]/', '', (string) $state))
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
                    ->visible(fn(Get $get): bool => in_array(strtolower((string) $get('journey_status')), ['sfl', 'underwriting', 'approved', 'sanctioned']))
                     ->hidden(fn () => auth()->user()->hasRole('Employee')),
            ]);
    }
}
