<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Tables\Components;

use Filament\Tables;

class CommonActions
{
    public static function standardCrud(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->color('info'),
            Tables\Actions\EditAction::make()
                ->color('warning'),
            Tables\Actions\DeleteAction::make()
                ->color('danger'),
        ];
    }

    public static function editOnly(): array
    {
        return [
            Tables\Actions\EditAction::make()
                ->color('warning'),
        ];
    }

    public static function viewEdit(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->color('info'),
            Tables\Actions\EditAction::make()
                ->color('warning'),
        ];
    }

    public static function standardBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->color('danger'),
            ]),
        ];
    }

    public static function bulkActionsWithExport(string $exporterClass): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->color('danger'),
                Tables\Actions\ExportBulkAction::make()
                    ->exporter($exporterClass)
                    ->label('Export Selected')
                    ->color('success'),
            ]),
        ];
    }

    public static function exportHeaderAction(string $exporterClass, string $label = 'Export All'): Tables\Actions\ExportAction
    {
        return Tables\Actions\ExportAction::make()
            ->exporter($exporterClass)
            ->label($label)
            ->color('success')
            ->icon('heroicon-o-arrow-down-tray');
    }

    public static function toggleActiveAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('toggle_active')
            ->label(fn ($record): string => $record->is_active ? 'Deactivate' : 'Activate')
            ->icon(fn ($record): string => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
            ->color(fn ($record): string => $record->is_active ? 'warning' : 'success')
            ->action(function ($record): void {
                $record->update(['is_active' => !$record->is_active]);
            })
            ->requiresConfirmation()
            ->modalDescription(fn ($record): string => 
                'Are you sure you want to ' . ($record->is_active ? 'deactivate' : 'activate') . ' this record?'
            );
    }
} 