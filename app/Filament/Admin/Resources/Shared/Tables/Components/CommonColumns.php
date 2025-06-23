<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Tables\Components;

use Filament\Tables;

class CommonColumns
{
    public static function title(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('title')
            ->searchable()
            ->sortable()
            ->wrap()
            ->limit(50)
            ->tooltip(fn ($record): string => $record->title ?? '');
    }

    public static function name(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('name')
            ->searchable()
            ->sortable()
            ->wrap()
            ->limit(50)
            ->tooltip(fn ($record): string => $record->name ?? '');
    }

    public static function code(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('code')
            ->label('Code')
            ->searchable()
            ->sortable()
            ->copyable()
            ->badge()
            ->color('primary')
            ->weight('medium');
    }

    public static function description(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('description')
            ->searchable()
            ->limit(100)
            ->wrap()
            ->toggleable()
            ->tooltip(fn ($record): string => $record->description ?? '');
    }

    public static function activeStatus(): Tables\Columns\IconColumn
    {
        return Tables\Columns\IconColumn::make('is_active')
            ->label('Status')
            ->boolean()
            ->trueIcon('heroicon-o-check-circle')
            ->falseIcon('heroicon-o-x-circle')
            ->trueColor('success')
            ->falseColor('danger')
            ->sortable();
    }

    public static function createdAt(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('created_at')
            ->label('Created At')
            ->dateTime('M d, Y H:i')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function updatedAt(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('updated_at')
            ->label('Updated At')
            ->dateTime('M d, Y H:i')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function startDate(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('start_date')
            ->label('Start Date')
            ->date('M d, Y')
            ->sortable()
            ->toggleable();
    }

    public static function endDate(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('end_date')
            ->label('End Date')
            ->date('M d, Y')
            ->sortable()
            ->toggleable();
    }
}
