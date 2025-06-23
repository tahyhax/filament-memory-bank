<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasCourseDetails
{
    protected static function courseDetails(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Course Details')
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('duration_hours')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(500)
                        ->suffix('hours')
                        ->placeholder('e.g., 40'),
                    Forms\Components\Select::make('difficulty_level')
                        ->required()
                        ->options([
                            'beginner' => 'Beginner',
                            'intermediate' => 'Intermediate',
                            'advanced' => 'Advanced',
                        ])
                        ->native(false),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active Status')
                        ->default(true)
                        ->inline(false),
                ]),
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('$')
                        ->step(0.01)
                        ->placeholder('Leave empty for free courses'),
                    Forms\Components\TextInput::make('instructor')
                        ->maxLength(255)
                        ->placeholder('e.g., Dr. Sarah Johnson'),
                ]),
            ]);
    }
} 