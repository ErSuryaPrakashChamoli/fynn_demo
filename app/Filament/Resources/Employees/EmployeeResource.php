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

                    TextInput::make('designation')
                        ->required(),

                    DatePicker::make('doj')
                        ->label('Date Of Joining'),

                    DatePicker::make('reporting_date'),

                    Select::make('superviser_id')
                        ->label('Superviser')
                        ->relationship('superviser', 'emp_name')
                        ->searchable()
                        ->preload(),

                    Select::make('manager_id')
                        ->label('Manager')
                        ->relationship('manager', 'emp_name')
                        ->searchable()
                        ->preload(),

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
                ->columns([

                    Tables\Columns\TextColumn::make('emp_id')
                        ->searchable()
                        ->sortable(),

                    Tables\Columns\TextColumn::make('emp_name')
                        ->searchable(),

                    Tables\Columns\TextColumn::make('designation'),

                    Tables\Columns\TextColumn::make('email'),

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
