<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasBasicCreditFields
{
    protected static function basicCreditFields(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make(4)->schema([
            Forms\Components\TextInput::make('credit_points')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(20)
                ->suffix('points')
                ->placeholder('e.g., 3'),
            Forms\Components\Select::make('credit_type')
                ->required()
                ->options([
                    'academic' => 'Academic',
                    'professional' => 'Professional',
                    'continuing_education' => 'Continuing Education',
                ])
                ->native(false),
            Forms\Components\Toggle::make('is_active')
                ->label('Active Status')
                ->default(true)
                ->inline(false),
            Forms\Components\TextInput::make('issuing_authority')
                ->maxLength(255)
                ->placeholder('e.g., Tech Education Board'),
        ]);
    }
} 