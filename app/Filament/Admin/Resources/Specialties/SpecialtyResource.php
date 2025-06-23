<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Specialties;

use App\Filament\Admin\Resources\Specialties\Components\Schemas\SpecialtySchema;
use App\Filament\Admin\Resources\Specialties\Components\Tables\SpecialtyTable;
use App\Filament\Admin\Resources\Specialties\Pages;
use App\Models\Specialty;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class SpecialtyResource extends Resource
{
    protected static ?string $model = Specialty::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return SpecialtySchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return SpecialtyTable::configure($table);
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
            'index' => Pages\ListSpecialties::route('/'),
            'create' => Pages\CreateSpecialty::route('/create'),
            'edit' => Pages\EditSpecialty::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code', 'industry', 'description'];
    }
}
