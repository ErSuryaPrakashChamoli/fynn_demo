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
                    ->visible(fn ($record): bool => $record->eligibility_status === 'not_eligible'),
            ])
            ->columns(2),

        Section::make('Journey')
            ->schema([
                TextEntry::make('company_category')
                    ->label('Company Category')
                    ->formatStateUsing(fn ($state) => $state ?: '-'),

                TextEntry::make('loan_applied')
                    ->label('Loan Applied For')
                    ->formatStateUsing(function ($state, $record) {
                        if ($state === 'other') {
                            return $record->other_loan_applied ?: '-';
                        }

                        return $state ?: '-';
                    }),

                TextEntry::make('bank_eligible_for')
                    ->label('Bank Eligible For')
                    ->formatStateUsing(function ($state, $record) {
                        if ($state === 'Other') {
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
                    ->visible(fn ($record): bool => $record->journey_status === 'not_approved'),
            ])
            ->columns(2),

        Section::make('Sanctioned Details')
            ->schema([
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
            // Updated visibility condition to check an array of allowed statuses
            ->visible(fn ($record): bool => in_array($record->journey_status, ['sfl', 'underwriting', 'approved', 'sanctioned'])),
    ]);

        
    }
}
