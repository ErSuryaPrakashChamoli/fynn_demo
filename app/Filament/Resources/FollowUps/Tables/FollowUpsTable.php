<?php

namespace App\Filament\Resources\FollowUps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\Action;

use App\Filament\Resources\FollowUps\FollowUpResource;


class FollowUpsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //

                Action::make('follow_up')
                ->label('Follow Up')
                ->icon('heroicon-o-phone')
                ->color('primary')
                ->url(fn ($record) => FollowUpResource::getUrl('create', [
                'customer' => $record->id,
                ]))

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                  Action::make('followup')
                    ->label('Follow Up')
                    ->icon('heroicon-o-phone')
                    ->color('warning')
                    ->url(fn ($record) => FollowUpResource::getUrl('create', [
                        'customer' => $record->id,
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
