<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Specialties\Components\Tables;

use App\Filament\Admin\Resources\Shared\Tables\Components\CommonActions;
use App\Filament\Admin\Resources\Shared\Tables\Components\CommonColumns;
use App\Filament\Admin\Resources\Shared\Tables\Components\CommonFilters;
use App\Filament\Exports\SpecialtyExporter;
use Filament\Tables;
use Filament\Tables\Table;

class SpecialtyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                CommonColumns::code(),
                CommonColumns::name(),
                Tables\Columns\TextColumn::make('specialty_category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'technical' => 'info',
                        'business' => 'success',
                        'healthcare' => 'danger',
                        'education' => 'warning',
                        'creative' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('required_experience_years')
                    ->label('Experience Level')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match (true) {
                        $state === 0 => 'Entry',
                        $state <= 2 => 'Junior',
                        $state <= 5 => 'Mid-level',
                        $state <= 10 => 'Senior',
                        default => 'Expert',
                    })
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'success',
                        $state <= 2 => 'info',
                        $state <= 5 => 'warning',
                        $state <= 10 => 'danger',
                        default => 'purple',
                    }),
                Tables\Columns\TextColumn::make('industry')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                CommonColumns::activeStatus(),
                CommonColumns::createdAt(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('specialty_category')
                    ->label('Category')
                    ->options([
                        'technical' => 'Technical',
                        'business' => 'Business',
                        'healthcare' => 'Healthcare',
                        'education' => 'Education',
                        'creative' => 'Creative',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('required_experience_years')
                    ->label('Experience Level')
                    ->options([
                        0 => 'Entry (0 years)',
                        1 => 'Junior (1-2 years)',
                        3 => 'Mid-level (3-5 years)',
                        6 => 'Senior (6-10 years)',
                        11 => 'Expert (10+ years)',
                    ])
                    ->query(function ($query, array $data) {
                        if (!$data['value']) return $query;
                        
                        return match ((int) $data['value']) {
                            0 => $query->where('required_experience_years', 0),
                            1 => $query->whereBetween('required_experience_years', [1, 2]),
                            3 => $query->whereBetween('required_experience_years', [3, 5]),
                            6 => $query->whereBetween('required_experience_years', [6, 10]),
                            11 => $query->where('required_experience_years', '>', 10),
                            default => $query,
                        };
                    }),
                CommonFilters::activeStatus(),
            ])
            ->actions([
                ...CommonActions::editOnly(),
                CommonActions::toggleActiveAction(),
            ])
            ->bulkActions(CommonActions::bulkActionsWithExport(SpecialtyExporter::class))
            ->headerActions([
                CommonActions::exportHeaderAction(SpecialtyExporter::class, 'Export All Specialties'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
