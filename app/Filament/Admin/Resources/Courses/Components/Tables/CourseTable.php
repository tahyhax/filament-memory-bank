<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Courses\Components\Tables;

use App\Filament\Admin\Resources\Shared\Tables\Components\CommonActions;
use App\Filament\Exports\CourseExporter;
use Filament\Tables\Table;

class CourseTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(CourseColumns::make())
            ->filters(CourseFilters::make())
            ->actions([
                ...CommonActions::editOnly(),
                CommonActions::toggleActiveAction(),
            ])
            ->bulkActions(CommonActions::bulkActionsWithExport(CourseExporter::class))
            ->headerActions([
                CommonActions::exportHeaderAction(CourseExporter::class, 'Export All Courses'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
} 