<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits\Components\Tables;

use App\Filament\Admin\Resources\Shared\Tables\Components\CommonActions;
use App\Filament\Exports\CreditExporter;
use Filament\Tables\Table;

class CreditTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(CreditColumns::make())
            ->filters(CreditFilters::make())
            ->actions([
                ...CommonActions::editOnly(),
                CommonActions::toggleActiveAction(),
            ])
            ->bulkActions(CommonActions::bulkActionsWithExport(CreditExporter::class))
            ->headerActions([
                CommonActions::exportHeaderAction(CreditExporter::class, 'Export All Credits'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
} 