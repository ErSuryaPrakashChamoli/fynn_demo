<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.emp_id')
                ->label('Emp ID')
                ->searchable(),

                TextColumn::make('employee.emp_name')
                    ->label('Employee'),

                TextColumn::make('email')
                    ->searchable(),

    

               TextColumn::make('roles.name')
                ->badge()
                ->label('Role'),

                // TextColumn::make('is_active')
                //     ->boolean(),
                IconColumn::make('is_active')
                ->label('Status')
                ->boolean(),

                TextColumn::make('created_at')
                    ->date('d M Y')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                  ViewAction::make(),
                   DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
