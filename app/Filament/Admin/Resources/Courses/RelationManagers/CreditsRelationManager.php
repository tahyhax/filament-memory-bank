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

class CreditsRelationManager extends RelationManager
{
    protected static string $relationship = 'credits';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Credit Information')
                    ->schema([
                        Forms\Components\Select::make('relatable_id')
                            ->label('Credit')
                            ->options(\App\Models\Credit::all()->mapWithKeys(fn ($credit) => [$credit->id => "{$credit->code} - {$credit->name}"]))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('relatable_type')
                            ->default(\App\Models\Credit::class),
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
                                    ->default('awarded')
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
                    ->label('Credit Code')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Credit Name')
                    ->searchable()
                    ->wrap()
                    ->limit(40),
                Tables\Columns\TextColumn::make('credit_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'academic' => 'info',
                        'professional' => 'success',
                        'continuing_education' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('credit_points')
                    ->label('Points')
                    ->suffix(' pts')
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
                Tables\Filters\SelectFilter::make('credit_type')
                    ->options([
                        'academic' => 'Academic',
                        'professional' => 'Professional',
                        'continuing_education' => 'Continuing Education',
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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Credit')
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
            ->emptyStateHeading('No Credits Associated')
            ->emptyStateDescription('This course has no credits associated with it yet.')
            ->emptyStateIcon('heroicon-o-trophy');
    }
}
