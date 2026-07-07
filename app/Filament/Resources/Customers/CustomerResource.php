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

use App\Filament\Resources\FollowUps\FollowUpResource;
use Filament\Actions\Action;

use Filament\Forms\Components\FileUpload;


use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;

use Filament\Facades\Filament;

use Filament\Forms\Components\CheckboxList;

use Filament\Actions\ImportAction;
use App\Filament\Imports\CustomerImporter;


use Illuminate\Database\Eloquent\Builder;


use Illuminate\Support\Facades\Auth;
use App\Filament\Exports\CustomerExporter;



use Filament\Actions\ExportAction;


use Filament\Actions\ExportBulkAction;


class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'customer_name';

  
    public static function form(Schema $schema): Schema
      {
         return CustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
          ->defaultSort('id', 'desc')
        
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
                Action::make('followup')
                    ->label('Follow Up')
                    ->icon('heroicon-o-phone')
                    ->color('warning')
                    ->url(fn ($record) => FollowUpResource::getUrl('create', [
                        'customer' => $record->id,
                    ])),
            ])
            ->headerActions([

                ExportAction::make()
                ->exporter(CustomerExporter::class)
                ->label('Export Customers')
                ->icon('heroicon-o-arrow-down-tray'),

                ImportAction::make()
                ->label('Import Customers')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->importer(CustomerImporter::class)
                ])
            ->toolbarActions([
               DeleteBulkAction::make(),
               ExportBulkAction::make()
                    ->exporter(CustomerExporter::class)
                    ->label('Export Selected'),
                    
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();


        // if (auth()->user()->hasRole('Admin')) {
        //     return $query;
        // }

        // return $query->where('employee_id', auth()->user()->employee_id);


        $currentUser = Auth::user()->employee;


        if (!$currentUser) {
                return $query;
        }

        

        if ($currentUser->designation == 1) {
            return $query->where('assigned_to', $currentUser->id);
        }

        if ($currentUser->designation == 2) {
                return $query->where(function (Builder $subQuery) use ($currentUser) {
                    $subQuery->where('assign_to', $currentUser->id) // खुद के कस्टमर
                        ->orWhereIn('assign_to', function ($employeesQuery) use ($currentUser) {
                            $employeesQuery->select('id')
                                ->from('employees')
                                ->where('superviser_id', $currentUser->id);
                        });
                });
            }

            if ($currentUser->designation == 3) {
        
                return $query->where(function (Builder $subQuery) use ($currentUser) {
                    $subQuery->where('assign_to', $currentUser->id) // खुद के कस्टमर
                        ->orWhereIn('assign_to', function ($employeesQuery) use ($currentUser) {
                            
                            $employeesQuery->select('id')
                                ->from('employees')
                                ->where('manager_id', $currentUser->id);
                        });
                });

               
            }

            if ($currentUser->designation == 4) {
                return $query->where(function (Builder $subQuery) use ($currentUser) {
                    $subQuery->where('assign_to', $currentUser->id)
                        ->orWhereIn('assign_to', function ($employeesQuery) use ($currentUser) {
                            $employeesQuery->select('id')
                                ->from('employees')
                                ->where('cluster_id', $currentUser->id);
                        });
                });
            }

            return $query;




    }

    
}
