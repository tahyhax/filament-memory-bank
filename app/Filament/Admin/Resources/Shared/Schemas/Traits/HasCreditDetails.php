<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Shared\Schemas\Traits;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;

trait HasCreditDetails
{
    use HasBasicCreditFields;
    use HasExtendedCreditFields;

    public static function simpleCreditDetails(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Credit Details')
            ->schema([
                self::basicCreditFields(),
            ]);
    }

    public static function creditDetails(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Credit Details')
            ->schema([
                self::basicCreditFields(),
                self::extendedCreditFields(),
            ]);
    }

    public static function fullCreditDetails(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Credit Details')
            ->schema([
                self::basicCreditFields(),
                self::extendedCreditFields(),
                self::additionalCreditFields(),
            ]);
    }

    public static function configurableCreditDetails(array $config = []): Group
    {
        $fields = [];
        
        // Basic credit fields (always available)
        if ($config['showBasic'] ?? true) {
            $fields = array_merge($fields, self::getBasicCreditFields());
        }
        
        // Extended credit fields (dates)
        if ($config['showExtended'] ?? false) {
            $fields = array_merge($fields, self::getExtendedCreditFields());
        }
        
        // Additional fields (certification)
        if ($config['showAdditional'] ?? false) {
            $fields = array_merge($fields, self::getAdditionalCreditFields());
        }
        
        // Custom fields from config
        if (isset($config['customFields']) && is_array($config['customFields'])) {
            $fields = array_merge($fields, $config['customFields']);
        }

        return Group::make($fields)
            ->label('Credit Details')
            ->columnSpanFull()
            ->columns(2);
    }

    private static function getBasicCreditFields(): array
    {
        return [
            TextInput::make('credit_points')
                ->label('Credit Points')
                ->numeric()
                ->required()
                ->minValue(0)
                ->maxValue(999),

            Select::make('credit_type')
                ->label('Credit Type')
                ->required()
                ->options([
                    'continuing_education' => 'Continuing Education',
                    'professional_development' => 'Professional Development',
                    'certification' => 'Certification',
                    'training' => 'Training',
                ])
                ->native(false),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),

            TextInput::make('issuing_authority')
                ->label('Issuing Authority')
                ->required()
                ->maxLength(255),
        ];
    }

    private static function getExtendedCreditFields(): array
    {
        return [
            DatePicker::make('valid_from')
                ->label('Valid From')
                ->required(),

            DatePicker::make('valid_until')
                ->label('Valid Until')
                ->after('valid_from'),
        ];
    }

    private static function getAdditionalCreditFields(): array
    {
        return [
            TextInput::make('certification_number')
                ->label('Certification Number')
                ->maxLength(100),

            TextInput::make('renewal_period_months')
                ->label('Renewal Period (Months)')
                ->numeric()
                ->minValue(1)
                ->maxValue(120),
        ];
    }
} 