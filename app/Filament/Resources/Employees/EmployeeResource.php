<?php

namespace App\Filament\Resources\Employees;

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Pages\ViewEmployee;
use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Employees\Schemas\EmployeeInfolist;
use App\Filament\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;


use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;


use Filament\Forms\Components\FileUpload;


use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;

use Filament\Forms\Components\DatePicker;

use Filament\Actions\ImportAction;
use App\Filament\Imports\CustomerImporter;
use App\Filament\Imports\EmployeeImporter;



use Filament\Schemas\Components\Utilities\Set;


class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
         return $schema->schema([

            Section::make('Employee Details')
                ->schema([

                    TextInput::make('emp_id')
                        ->label('Employee ID')
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('emp_name')
                        ->required(),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('position')
                       ->label('Designation')
                        ->required(),    

                    // TextInput::make('designation')
                    //     ->required(),

                        Select::make('designation')
                        ->label('Position')
                        ->options([
                            '1' => 'Caller',
                            '2' => 'Team Leader',
                            '3' => 'Manager',
                            '4' => 'Cluster Manager',
                        ])
                        ->required()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Set $set, $state) {
                            if ($state !== '1') {
                                $set('superviser_id', null);
                            }
                            // अगर यूज़र Manager या Cluster Manager चुनता है, तो मैनेजर को भी खाली कर दो
                            if (in_array($state, ['3', '4'])) {
                                $set('manager_id', null);
                            }
                        })
                        ->native(false),

                

                    Select::make('category')
                    ->label('Target Category')
                    ->options([
                        '2500000' => 'Silver',
                        '3000000' => 'Gold',
                        '3500000' => 'Platinum',
                    ])
                    ->required() 
                    ->native(false),

                   

                    Select::make('superviser_id')
                        ->label('Superviser')
                        ->relationship('superviser', 'emp_name')
                        ->searchable()
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->emp_name} - ({$record->emp_id})")
                        ->visible(fn (Get $get) => in_array($get('designation'), ['1']))
                        ->required(fn (Get $get) => $get('designation') === '1')
                        ->preload(),

                    Select::make('manager_id')
                        ->label('Manager')
                        ->relationship('manager', 'emp_name')
                        ->searchable()
                         ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->emp_name} - ({$record->emp_id})")
                         ->visible(fn (Get $get) => in_array($get('designation'), ['1', '2']))
                         ->required(fn (Get $get) => in_array($get('designation'), ['3', '4']))
                        ->preload(),

                    Select::make('cluster_id')
                    ->label('Cluster Manager')
                    ->relationship('clusterManager', 'emp_name')
                    ->searchable()
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->emp_name} - ({$record->emp_id})")
                        ->visible(fn (Get $get) => in_array($get('designation'), ['1', '2' , '3']))
                        ->required(fn (Get $get) => in_array($get('designation'), ['3', '4']))
                    ->preload(),


                    DatePicker::make('doj')
                        ->label('Date Of Joining'),

                    DatePicker::make('reporting_date'),

                    TextInput::make('cost_center'),

                    TextInput::make('unit_name'),

                ])
                ->columns(2)
                ->columnSpanFull(),
    ]);
        // return EmployeeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EmployeeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {

            return $table
                ->defaultSort('id', 'desc')
                ->columns([

                    Tables\Columns\TextColumn::make('emp_id')
                        ->searchable()
                        ->sortable(),

                    Tables\Columns\TextColumn::make('emp_name')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('designation'),

                    Tables\Columns\TextColumn::make('email'),

                    Tables\Columns\TextColumn::make('category')
                        ->label('Target Category'),

                    Tables\Columns\TextColumn::make('superviser.emp_name')
                        ->label('Superviser'),

                    Tables\Columns\TextColumn::make('manager.emp_name')
                        ->label('Manager'),

                    Tables\Columns\TextColumn::make('cost_center'),

                    Tables\Columns\TextColumn::make('unit_name'),

                    Tables\Columns\TextColumn::make('doj')
                        ->date('d M Y'),

                    Tables\Columns\TextColumn::make('reporting_date')
                        ->date('d M Y'),

                ])
                ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
                ])
                  ->headerActions([
                ImportAction::make()
                ->label('Import Employees')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->importer(EmployeeImporter::class)
                ])
                ->toolbarActions([
                    DeleteBulkAction::make(),
                ]);

    
        // return EmployeesTable::configure($table);
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'view' => ViewEmployee::route('/{record}'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
