<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasExtendedCreditFields
{
    protected static function extendedCreditFields(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make(2)->schema([
            Forms\Components\DatePicker::make('valid_from')
                ->label('Valid From')
                ->displayFormat('M d, Y')
                ->default(now()),
            Forms\Components\DatePicker::make('valid_until')
                ->label('Valid Until')
                ->displayFormat('M d, Y')
                ->after('valid_from')
                ->placeholder('Select expiration date'),
        ]);
    }

    protected static function additionalCreditFields(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make(2)->schema([
            Forms\Components\TextInput::make('certification_number')
                ->maxLength(255)
                ->placeholder('e.g., CERT-2024-001'),
            Forms\Components\TextInput::make('renewal_period_months')
                ->numeric()
                ->minValue(1)
                ->maxValue(120)
                ->suffix('months')
                ->placeholder('e.g., 12'),
        ]);
    }
} 