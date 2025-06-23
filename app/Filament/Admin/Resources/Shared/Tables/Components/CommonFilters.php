<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Tables\Components;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CommonFilters
{
    public static function activeStatus(): Tables\Filters\TernaryFilter
    {
        return Tables\Filters\TernaryFilter::make('is_active')
            ->label('Active Status')
            ->placeholder('All records')
            ->trueLabel('Active only')
            ->falseLabel('Inactive only');
    }

    public static function dateRange(string $column = 'created_at', string $label = 'Date Range'): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make($column)
            ->form([
                \Filament\Forms\Components\DatePicker::make('from')
                    ->label('From Date'),
                \Filament\Forms\Components\DatePicker::make('until')
                    ->label('Until Date'),
            ])
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return $query
                    ->when(
                        $data['from'],
                        fn (Builder $query, $date): Builder => $query->whereDate($column, '>=', $date),
                    )
                    ->when(
                        $data['until'],
                        fn (Builder $query, $date): Builder => $query->whereDate($column, '<=', $date),
                    );
            })
            ->label($label);
    }

    public static function upcoming(string $dateColumn = 'start_date'): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make('upcoming')
            ->query(fn (Builder $query): Builder => $query->where($dateColumn, '>=', now()))
            ->label('Upcoming Only');
    }

    public static function past(string $dateColumn = 'end_date'): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make('past')
            ->query(fn (Builder $query): Builder => $query->where($dateColumn, '<', now()))
            ->label('Past Only');
    }

    public static function hasValue(string $column, string $label): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make("has_{$column}")
            ->query(fn (Builder $query): Builder => $query->whereNotNull($column))
            ->label("Has {$label}");
    }

    public static function withoutValue(string $column, string $label): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make("without_{$column}")
            ->query(fn (Builder $query): Builder => $query->whereNull($column))
            ->label("Without {$label}");
    }
} 