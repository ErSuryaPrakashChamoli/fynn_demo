<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        // return $schema
        //     ->components([
                
        //         //
        //     ]);


         return $schema->schema([
            Section::make('Customer Details')
                ->schema([
                    TextEntry::make('customer_name')->label('Customer Name'),

                    TextEntry::make('mobile_no')
                    ->label('Mobile No')
                    ->formatStateUsing(function (?string $state): string {
                            if (blank($state) || strlen($state) < 4) {
                                return $state ?? '-';
                            }

                            return substr($state, 0, 4) . 'XXXXXX';
                        }),
                    TextEntry::make('email')->label('Email'),
                    TextEntry::make('pan_number')->label('PAN Number'),
                    TextEntry::make('job_location')->label('Job Location'),
                    TextEntry::make('residence_location')->label('Residence Location'),
                    TextEntry::make('salary')->label('Salary'),
                    TextEntry::make('current_location')->label('Current Location'),
                    TextEntry::make('company_category')->label('Company Category'),
                    TextEntry::make('bank_eligible_for')->label('Bank Eligible For'),
                    TextEntry::make('loan_applied')->label('Loan Applied'),
                ])
                ->columns(2),

            Section::make('Eligibility')
                ->schema([
                    TextEntry::make('eligibility_status')->label('Eligibility'),
                    TextEntry::make('eligibility_reason')->label('Not Eligible Reason'),
                ])
                ->columns(2),

            Section::make('Journey')
                ->schema([
                    TextEntry::make('journey_status')->label('Journey'),
                    TextEntry::make('journey_not_approved_reason')->label('Not Approved Reason'),
                ])
                ->columns(2),

            Section::make('Sanctioned Details')
                ->schema([
                    TextEntry::make('sanctioned_bank')->label('Bank'),
                    TextEntry::make('sanctioned_loan_amount')->label('Loan Amount'),
                    TextEntry::make('cashback')->label('Cashback'),
                    TextEntry::make('subvention')->label('Subvention'),
                    TextEntry::make('payout_rate')->label('Payout Rate'),
                    TextEntry::make('bank_condition')->label('Bank Condition'),
                    TextEntry::make('attachment_required')->label('Attachment Required'),

                    TextEntry::make('attachment_file')
                        ->label('Attachment File')
                        ->url(fn ($state) => $state ? asset('storage/' . $state) : null, shouldOpenInNewTab: true)
                        ->openUrlInNewTab(),
                ])
                ->columns(2),
        ]);
    }
}
