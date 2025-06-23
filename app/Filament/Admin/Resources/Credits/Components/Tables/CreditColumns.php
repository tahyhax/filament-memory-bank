<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits\Components\Tables;

use App\Filament\Admin\Resources\Shared\Tables\Components\CommonColumns;
use Filament\Tables;

class CreditColumns
{
    public static function make(): array
    {
        return [
            Tables\Columns\TextColumn::make('code')
                ->label('Credit Code')
                ->searchable()
                ->sortable()
                ->copyable()
                ->badge()
                ->color('warning'),
            Tables\Columns\TextColumn::make('name')
                ->label('Credit Name')
                ->searchable()
                ->sortable()
                ->wrap()
                ->limit(50),
            Tables\Columns\TextColumn::make('credit_type')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'academic' => 'info',
                    'professional' => 'success',
                    'continuing_education' => 'warning',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('credit_points')
                ->label('Points')
                ->numeric()
                ->sortable()
                ->suffix(' pts'),
            Tables\Columns\TextColumn::make('issuing_authority')
                ->searchable()
                ->limit(30)
                ->toggleable(),
            CommonColumns::activeStatus(),
            Tables\Columns\TextColumn::make('valid_until')
                ->date('M d, Y')
                ->sortable()
                ->toggleable(),
            CommonColumns::createdAt(),
        ];
    }
} 