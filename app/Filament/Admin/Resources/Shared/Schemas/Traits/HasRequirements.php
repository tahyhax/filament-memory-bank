<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasRequirements
{
    protected static function requirements(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Requirements')
            ->schema([
                Forms\Components\KeyValue::make('requirements')
                    ->keyLabel('Requirement Type')
                    ->valueLabel('Requirement Value')
                    ->addActionLabel('Add Requirement')
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }
} 