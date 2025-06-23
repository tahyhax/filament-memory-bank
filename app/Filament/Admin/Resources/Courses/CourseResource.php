<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Courses;

use App\Filament\Admin\Resources\Courses\Components\Schemas\CourseSchema;
use App\Filament\Admin\Resources\Courses\Components\Tables\CourseTable;
use App\Filament\Admin\Resources\Courses\Pages;
use App\Filament\Admin\Resources\Courses\RelationManagers;
use App\Models\Course;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return CourseSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return CourseTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CreditsRelationManager::class,
            RelationManagers\SpecialtiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['credits', 'specialties']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'code', 'description', 'instructor'];
    }
}
