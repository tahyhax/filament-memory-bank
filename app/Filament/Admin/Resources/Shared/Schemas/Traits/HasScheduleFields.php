<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;

trait HasScheduleFields
{
    protected static function scheduleFields(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Schedule')
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date')
                        ->displayFormat('M d, Y')
                        ->placeholder('Select start date'),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date')
                        ->displayFormat('M d, Y')
                        ->after('start_date')
                        ->placeholder('Select end date'),
                ]),
            ]);
    }
} 