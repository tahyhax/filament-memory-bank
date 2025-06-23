<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasStatusFields
{
    protected static function statusFields(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Status & Settings')
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active Status')
                        ->default(true)
                        ->inline(false),
                    Forms\Components\Toggle::make('is_featured')
                        ->label('Featured')
                        ->default(false)
                        ->inline(false),
                    Forms\Components\Toggle::make('is_public')
                        ->label('Public Access')
                        ->default(true)
                        ->inline(false),
                ]),
            ]);
    }
} 