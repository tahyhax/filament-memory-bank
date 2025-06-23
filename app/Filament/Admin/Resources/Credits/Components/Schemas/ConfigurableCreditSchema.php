<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits\Components\Schemas;

use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditBasicInformation;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditDetails;
use Filament\Forms\Form;

class ConfigurableCreditSchema
{
    use HasCreditBasicInformation;
    use HasCreditDetails;

    public static function makeSimple(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::configurableCreditDetails([
                'showBasic' => true,
                'showExtended' => false,
                'showAdditional' => false,
            ]), // 4 поля
        ]);
    }

    public static function makeStandard(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::configurableCreditDetails([
                'showBasic' => true,
                'showExtended' => true,
                'showAdditional' => false,
            ]), // 6 полей
        ]);
    }

    public static function makeFull(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::configurableCreditDetails([
                'showBasic' => true,
                'showExtended' => true,
                'showAdditional' => true,
            ]), // 8 полей
        ]);
    }
} 