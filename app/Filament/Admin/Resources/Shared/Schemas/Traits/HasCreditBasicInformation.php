<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasCreditBasicInformation
{
    protected static function basicInformation(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Basic Information')
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Credit name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Enter credit name...'),
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g., CREDIT001'),
                ]),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->placeholder('Detailed description...')
                    ->columnSpanFull(),
            ]);
    }
}
