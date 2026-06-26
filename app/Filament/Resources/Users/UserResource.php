<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
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
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Hash;


use Filament\Forms\Components\FileUpload;


use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
         return $schema->schema([

                Section::make('User Account')
                    ->schema([

                        Select::make('employee_id')
                            ->label('Employee')
                            ->relationship('employee', 'emp_name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $employee = \App\Models\Employee::find($state);

                                if ($employee) {
                                    $set('name', $employee->emp_name);
                                    $set('email', $employee->email);
                                }
                            })
                            ->required(),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'manager' => 'Manager',
                                'supervisor' => 'Supervisor',
                                'employee' => 'Employee',
                            ])
                            ->default('employee')
                            ->required(),

                       TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->same('password_confirmation'),

                      TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->required(fn (string $operation): bool => $operation === 'create'),

                      Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
        // return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([

                Tables\Columns\TextColumn::make('employee.emp_id')
                    ->label('Emp ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('employee.emp_name')
                    ->label('Employee'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'manager',
                        'info' => 'supervisor',
                        'success' => 'employee',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y')
                    ->sortable(),

            ])
             ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
                ])
                ->toolbarActions([
                    DeleteBulkAction::make(),
                ]);
        // return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
