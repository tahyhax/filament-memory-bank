<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasSpecialtyDetails
{
    protected static function specialtyDetails(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Specialty Details')
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('specialty_category')
                        ->label('Category')
                        ->required()
                        ->options([
                            'technical' => 'Technical',
                            'business' => 'Business',
                            'healthcare' => 'Healthcare',
                            'education' => 'Education',
                            'creative' => 'Creative',
                        ])
                        ->native(false),
                    Forms\Components\TextInput::make('required_experience_years')
                        ->label('Required Experience (Years)')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(50)
                        ->default(0)
                        ->helperText('0 = Entry level, 1-2 = Junior, 3-5 = Mid-level, 6-10 = Senior, 10+ = Expert'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active Status')
                        ->default(true)
                        ->inline(false),
                ]),
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('industry')
                        ->maxLength(255)
                        ->placeholder('e.g., Software Development'),
                    Forms\Components\TextInput::make('certification_body')
                        ->maxLength(255)
                        ->placeholder('e.g., Professional Certification Institute'),
                    Forms\Components\Toggle::make('certification_required')
                        ->label('Certification Required')
                        ->default(false)
                        ->inline(false),
                ]),
                Forms\Components\TagsInput::make('skills_required')
                    ->label('Skills Required')
                    ->placeholder('Add skills required...')
                    ->columnSpanFull(),
            ]);
    }
} 