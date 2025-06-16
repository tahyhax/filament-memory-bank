<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CreditResource\Pages;
use App\Filament\Admin\Resources\CreditResource\RelationManagers;
use App\Models\Credit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Exports\CreditExporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditResource extends Resource
{
    protected static ?string $model = Credit::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Credit Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Web Development Foundation Certificate'),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('e.g., WD-1001'),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Detailed credit description...')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Credit Details')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('credit_points')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(20)
                                    ->suffix('points'),
                                Forms\Components\Select::make('credit_type')
                                    ->required()
                                    ->options([
                                        'academic' => 'Academic',
                                        'professional' => 'Professional',
                                        'continuing_education' => 'Continuing Education',
                                    ])
                                    ->native(false),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Status')
                                    ->default(true),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('issuing_authority')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Tech Education Board'),
                                Forms\Components\DatePicker::make('valid_from')
                                    ->label('Valid From')
                                    ->displayFormat('M d, Y')
                                    ->default(now()),
                            ]),
                        Forms\Components\DatePicker::make('valid_until')
                            ->label('Valid Until')
                            ->displayFormat('M d, Y')
                            ->after('valid_from'),
                    ]),

                Forms\Components\Section::make('Requirements')
                    ->schema([
                        Forms\Components\KeyValue::make('requirements')
                            ->keyLabel('Requirement Type')
                            ->valueLabel('Requirement Value')
                            ->addActionLabel('Add Requirement')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Credit Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),
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
                    ->numeric()
                    ->sortable()
                    ->suffix(' pts'),
                Tables\Columns\TextColumn::make('issuing_authority')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('valid_until')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('credit_type')
                    ->options([
                        'academic' => 'Academic',
                        'professional' => 'Professional',
                        'continuing_education' => 'Continuing Education',
                    ])
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\Filter::make('valid')
                    ->query(fn (Builder $query): Builder => $query->where('valid_until', '>=', now()))
                    ->label('Currently Valid'),
                Tables\Filters\Filter::make('high_value')
                    ->query(fn (Builder $query): Builder => $query->where('credit_points', '>=', 5))
                    ->label('High Value (5+ points)'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(CreditExporter::class)
                        ->label('Export Selected'),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(CreditExporter::class)
                    ->label('Export All Credits')
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
