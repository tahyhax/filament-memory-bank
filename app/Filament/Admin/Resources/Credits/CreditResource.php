<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits;

use App\Filament\Admin\Resources\Credits\Components\Schemas\CreditSchema;
use App\Filament\Admin\Resources\Credits\Components\Tables\CreditTable;
use App\Filament\Admin\Resources\Credits\Pages;
use App\Models\Credit;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class CreditResource extends Resource
{
    protected static ?string $model = Credit::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return CreditSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return CreditTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCredits::route('/'),
            'create' => Pages\CreateCredit::route('/create'),
            'edit' => Pages\EditCredit::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code', 'issuing_authority', 'description'];
    }
}
