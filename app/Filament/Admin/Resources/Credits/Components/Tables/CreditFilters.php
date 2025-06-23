<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits\Components\Tables;

use App\Filament\Admin\Resources\Shared\Tables\Components\CommonFilters;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CreditFilters
{
    public static function make(): array
    {
        return [
            Tables\Filters\SelectFilter::make('credit_type')
                ->options([
                    'academic' => 'Academic',
                    'professional' => 'Professional',
                    'continuing_education' => 'Continuing Education',
                ])
                ->multiple()
                ->label('Credit Type'),
            
            CommonFilters::activeStatus(),
            
            Tables\Filters\Filter::make('valid')
                ->query(fn (Builder $query): Builder => $query->where('valid_until', '>=', now()))
                ->label('Currently Valid'),
                
            Tables\Filters\Filter::make('high_value')
                ->query(fn (Builder $query): Builder => $query->where('credit_points', '>=', 5))
                ->label('High Value (5+ points)'),
                
            Tables\Filters\Filter::make('issuing_authority')
                ->form([
                    \Filament\Forms\Components\TextInput::make('authority')
                        ->label('Issuing Authority'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['authority'],
                        fn (Builder $query, $authority): Builder => $query->where('issuing_authority', 'like', "%{$authority}%"),
                    );
                })
                ->label('Filter by Authority'),
                
            CommonFilters::dateRange('valid_until', 'Valid Until Date Range'),
        ];
    }
} 