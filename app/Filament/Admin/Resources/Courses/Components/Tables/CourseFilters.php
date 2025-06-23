<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Courses\Components\Tables;

use App\Filament\Admin\Resources\Shared\Tables\Components\CommonFilters;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CourseFilters
{
    public static function make(): array
    {
        return [
            Tables\Filters\SelectFilter::make('difficulty_level')
                ->options([
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                ])
                ->multiple()
                ->label('Difficulty Level'),
            
            CommonFilters::activeStatus(),
            
            CommonFilters::hasValue('price', 'Price'),
            
            CommonFilters::withoutValue('price', 'Price'),
            
            CommonFilters::upcoming('start_date'),
            
            Tables\Filters\Filter::make('instructor')
                ->form([
                    \Filament\Forms\Components\TextInput::make('instructor')
                        ->label('Instructor Name'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['instructor'],
                        fn (Builder $query, $instructor): Builder => $query->where('instructor', 'like', "%{$instructor}%"),
                    );
                })
                ->label('Filter by Instructor'),
                
            CommonFilters::dateRange('start_date', 'Start Date Range'),
        ];
    }
} 