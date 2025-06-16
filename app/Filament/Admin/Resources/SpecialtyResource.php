<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SpecialtyResource\Pages;
use App\Filament\Admin\Resources\SpecialtyResource\RelationManagers;
use App\Models\Specialty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Exports\SpecialtyExporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialtyResource extends Resource
{
    protected static ?string $model = Specialty::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Specialty Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Frontend Web Developer'),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('e.g., FE-101'),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Detailed specialty description...')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Specialty Details')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('specialty_category')
                                    ->required()
                                    ->options([
                                        'technical' => 'Technical',
                                        'business' => 'Business',
                                        'creative' => 'Creative',
                                        'healthcare' => 'Healthcare',
                                        'education' => 'Education',
                                        'other' => 'Other',
                                    ])
                                    ->native(false),
                                Forms\Components\TextInput::make('industry')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Technology'),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->default(true),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('required_experience_years')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(20)
                                    ->suffix('years')
                                    ->default(0),
                                Forms\Components\Toggle::make('certification_required')
                                    ->label('Certification Required'),
                            ]),
                        Forms\Components\TextInput::make('certification_body')
                            ->maxLength(255)
                            ->placeholder('e.g., Oracle Certified Professional')
                            ->visible(fn (Forms\Get $get) => $get('certification_required')),
                    ]),

                Forms\Components\Section::make('Skills Required')
                    ->schema([
                        Forms\Components\TagsInput::make('skills_required')
                            ->placeholder('Add skills...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Specialty Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),
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
                Tables\Columns\TextColumn::make('industry')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('required_experience_years')
                    ->label('Experience')
                    ->numeric()
                    ->sortable()
                    ->suffix(' yrs'),
                Tables\Columns\IconColumn::make('certification_required')
                    ->label('Cert. Required')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('skills_required')
                    ->label('Skills')
                    ->formatStateUsing(function ($state): string {
                        if (empty($state)) {
                            return 'No skills specified';
                        }
                        
                        // Ensure we have an array
                        if (is_string($state)) {
                            $skills = json_decode($state, true) ?? [];
                        } elseif (is_array($state)) {
                            $skills = $state;
                        } else {
                            return 'Invalid data';
                        }
                        
                        if (empty($skills)) {
                            return 'No skills specified';
                        }
                        
                        return implode(', ', array_slice($skills, 0, 3)) . (count($skills) > 3 ? '...' : '');
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('specialty_category')
                    ->options([
                        'technical' => 'Technical',
                        'business' => 'Business',
                        'creative' => 'Creative',
                        'healthcare' => 'Healthcare',
                        'education' => 'Education',
                        'other' => 'Other',
                    ])
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('certification_required')
                    ->label('Certification Required'),
                Tables\Filters\Filter::make('entry_level')
                    ->query(fn (Builder $query): Builder => $query->where('required_experience_years', '<=', 1))
                    ->label('Entry Level (0-1 years)'),
                Tables\Filters\Filter::make('senior_level')
                    ->query(fn (Builder $query): Builder => $query->where('required_experience_years', '>=', 5))
                    ->label('Senior Level (5+ years)'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(SpecialtyExporter::class)
                        ->label('Export Selected'),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(SpecialtyExporter::class)
                    ->label('Export All Specialties')
                    ->color('success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
