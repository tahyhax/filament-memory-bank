<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Courses\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialtiesRelationManager extends RelationManager
{
    protected static string $relationship = 'specialties';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Specialty Information')
                    ->schema([
                        Forms\Components\Select::make('relatable_id')
                            ->label('Specialty')
                            ->options(\App\Models\Specialty::all()->mapWithKeys(fn ($specialty) => [$specialty->id => "{$specialty->code} - {$specialty->name}"]))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('relatable_type')
                            ->default(\App\Models\Specialty::class),
                    ]),

                Forms\Components\Section::make('Relationship Details')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('relation_type')
                                    ->required()
                                    ->options([
                                        'prerequisite' => 'Prerequisite (Required Before)',
                                        'corequisite' => 'Corequisite (Required Together)',
                                        'recommended' => 'Recommended',
                                        'awarded' => 'Awarded Upon Completion',
                                    ])
                                    ->default('recommended')
                                    ->native(false),
                                Forms\Components\Toggle::make('is_required')
                                    ->label('Required')
                                    ->default(false),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10)
                                    ->default(1)
                                    ->helperText('Priority/Weight (1-10)'),
                                Forms\Components\Textarea::make('notes')
                                    ->rows(2)
                                    ->placeholder('Additional notes about this relationship...'),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Specialty Code')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Specialty Name')
                    ->searchable()
                    ->wrap()
                    ->limit(40),
                Tables\Columns\TextColumn::make('specialty_category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'technical' => 'primary',
                        'business' => 'success',
                        'creative' => 'warning',
                        'healthcare' => 'danger',
                        'education' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('required_experience_years')
                    ->label('Experience')
                    ->suffix(' yrs')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('pivot.relation_type')
                    ->label('Relation Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'prerequisite' => 'danger',
                        'corequisite' => 'warning',
                        'recommended' => 'info',
                        'awarded' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('pivot.is_required')
                    ->label('Required')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('pivot.weight')
                    ->label('Weight')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('pivot.notes')
                    ->label('Notes')
                    ->limit(30)
                    ->placeholder('No notes'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('specialty_category')
                    ->label('Category')
                    ->options([
                        'technical' => 'Technical',
                        'business' => 'Business',
                        'creative' => 'Creative',
                        'healthcare' => 'Healthcare',
                        'education' => 'Education',
                        'other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('pivot.relation_type')
                    ->label('Relation Type')
                    ->options([
                        'prerequisite' => 'Prerequisite',
                        'corequisite' => 'Corequisite',
                        'recommended' => 'Recommended',
                        'awarded' => 'Awarded',
                    ]),
                Tables\Filters\TernaryFilter::make('pivot.is_required')
                    ->label('Required'),
                Tables\Filters\TernaryFilter::make('certification_required')
                    ->label('Certification Required'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Specialty')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Specialties Associated')
            ->emptyStateDescription('This course has no specialties associated with it yet.')
            ->emptyStateIcon('heroicon-o-star');
    }
}
