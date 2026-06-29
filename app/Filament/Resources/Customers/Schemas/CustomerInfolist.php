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
            Section::make('Customer Basic Details')
                ->schema([
                    TextEntry::make('customer_name')
                        ->label('Customer Name'),

                    TextEntry::make('mobile_no')
                        ->label('Mobile No'),

                    TextEntry::make('email')
                        ->label('Email'),

                    TextEntry::make('pan_number')
                        ->label('PAN Number'),

                    TextEntry::make('job_location')
                        ->label('Job Location'),

                    TextEntry::make('residence_location')
                        ->label('Residence Location'),

                    TextEntry::make('salary')
                        ->label('Salary')
                        ->formatStateUsing(fn ($state) => filled($state) ? '₹' . number_format((float) $state, 0) : '-'),

                    TextEntry::make('current_location')
                        ->label('Current Location'),

                    TextEntry::make('eligibility_status')
                        ->label('Eligibility')
                        ->badge(),

                    TextEntry::make('eligibility_reason')
                        ->label('Not Eligible Reason')
                        ->visible(fn ($record): bool => strtolower((string) $record->eligibility_status) === 'not_eligible'),

                        TextEntry::make('company_category')
                        ->label('Company Category')
                        ->formatStateUsing(fn ($state) => $state ?: '-'),

                    TextEntry::make('loan_applied')
                        ->label('Loan Applied For')
                        ->formatStateUsing(function ($state, $record) {
                            if (strtolower((string) $state) === 'other') {
                                return $record->other_loan_applied ?: '-';
                            }
                            return $state ?: '-';
                        }),

                    TextEntry::make('bank_eligible_for')
                        ->label('Bank Eligible For')
                        ->formatStateUsing(function ($state, $record) {
                            if (strtolower((string) $state) === 'other') {
                                return $record->other_bank_eligible_for ?: '-';
                            }
                            return $state ?: '-';
                        }),

                    TextEntry::make('journey_status')
                        ->label('Journey')
                        ->badge()
                        ->formatStateUsing(fn ($state) => $state ?: '-'),

                    TextEntry::make('journey_not_approved_reason')
                        ->label('Not Approved Reason')
                        ->visible(fn ($record): bool => strtolower((string) $record->journey_status) === 'not_approved'),

                    // Shows rejection remarks as long as they aren't empty
                    TextEntry::make('not_approved_remarks')
                        ->label('Rejection Remarks')
                        ->columnSpanFull()
                        ->visible(fn ($record): bool => filled($record->not_approved_remarks)),
                ])
                ->columnSpanFull()
                ->columns(3),

           

            Section::make('Sanctioned Details')
                ->schema([
                    TextEntry::make('channel')
                        ->label('Channel')
                        ->visible(fn ($record): bool => strtolower((string) $record->journey_status) === 'sanctioned'),

                    TextEntry::make('application_no')
                        ->label('Application No'),

                    TextEntry::make('lan_no')
                        ->label('LAN No'),

                    TextEntry::make('sanctioned_bank')
                        ->label('Bank'),

                    TextEntry::make('sanctioned_loan_amount')
                        ->label('Loan Amount')
                        ->formatStateUsing(fn ($state) => filled($state) ? '₹' . number_format((float) $state, 0) : '-'),

                    TextEntry::make('cashback')
                        ->label('Cashback')
                        ->formatStateUsing(fn ($state) => filled($state) ? '₹' . number_format((float) $state, 0) : '-'),

                    TextEntry::make('subvention')
                        ->label('Subvention')
                        ->formatStateUsing(fn ($state) => filled($state) ? '₹' . number_format((float) $state, 0) : '-'),

                    TextEntry::make('payout_rate')
                        ->label('Payout Rate'),

                    TextEntry::make('bank_condition')
                        ->label('Bank Condition')
                        ->columnSpanFull(),

                    TextEntry::make('attachment_required')
                        ->label('Attachment Required'),

                    TextEntry::make('attachment_file')
                        ->label('Attachment File'),
                ])
                ->columns(2)
                ->visible(fn ($record): bool => in_array(strtolower((string) $record->journey_status), ['sfl', 'underwriting', 'approved', 'sanctioned'])),
                
            // CREATED A DEDICATED REMARKS SECTION FOR THE AUDIT TRAIL
            Section::make('Remarks History')
                ->schema([
                    TextEntry::make('sfl_remarks')
                        ->label('SFL Remarks')
                        ->columnSpanFull()
                        ->visible(fn ($record): bool => filled($record->sfl_remarks)),

                    TextEntry::make('underwriting_remarks')
                        ->label('Underwriting Remarks')
                        ->columnSpanFull()
                        ->visible(fn ($record): bool => filled($record->underwriting_remarks)),

                    TextEntry::make('approved_remarks')
                        ->label('Approved Remarks')
                        ->columnSpanFull()
                        ->visible(fn ($record): bool => filled($record->approved_remarks)),

                    TextEntry::make('sanctioned_remarks')
                        ->label('Sanctioned Remarks')
                        ->columnSpanFull()
                        ->visible(fn ($record): bool => filled($record->sanctioned_remarks)),
                ])
                ->columns(1)
                // This entire section hides if NO remarks have been typed yet
                ->hidden(fn ($record): bool => 
                    blank($record->sfl_remarks) && 
                    blank($record->underwriting_remarks) && 
                    blank($record->approved_remarks) && 
                    blank($record->sanctioned_remarks)
                ),
        ]);

        
    }
}
