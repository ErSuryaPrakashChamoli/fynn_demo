<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


use Filament\Actions\ViewAction;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //

                        TextColumn::make('created_at')
                        ->dateTime('d M Y H:i')
                        ->sortable(),

                        TextColumn::make('causer.name')
                        ->label('User')
                        ->placeholder('System')
                        ->searchable(),

                        // BadgeColumn::make('event')
                        // ->colors([
                        // 'success' => 'created',
                        // 'warning' => 'updated',
                        // 'danger' => 'deleted',
                        // ]),

                        TextColumn::make('subject_type')
                        ->label('Module')
                        ->formatStateUsing(fn ($state) => class_basename($state)),

                        TextColumn::make('subject_id')
                        ->label('Record ID'),

                        TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
