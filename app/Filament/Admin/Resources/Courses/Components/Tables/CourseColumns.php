<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Courses\Components\Tables;

use App\Filament\Admin\Resources\Shared\Tables\Components\CommonColumns;
use Filament\Tables;

class CourseColumns
{
    public static function make(): array
    {
        return [
            CommonColumns::code(),
            CommonColumns::title(),
            Tables\Columns\TextColumn::make('difficulty_level')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'beginner' => 'success',
                    'intermediate' => 'warning',
                    'advanced' => 'danger',
                }),
            Tables\Columns\TextColumn::make('duration_hours')
                ->label('Duration')
                ->numeric()
                ->sortable()
                ->suffix(' hrs'),
            Tables\Columns\TextColumn::make('price')
                ->money('USD')
                ->sortable()
                ->placeholder('Free'),
            CommonColumns::activeStatus(),
            Tables\Columns\TextColumn::make('instructor')
                ->searchable()
                ->toggleable()
                ->limit(30),
            CommonColumns::startDate(),
            CommonColumns::createdAt(),
        ];
    }
} 