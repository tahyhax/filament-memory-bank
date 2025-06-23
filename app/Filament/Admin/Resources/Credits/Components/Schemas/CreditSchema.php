<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits\Components\Schemas;

use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditBasicInformation;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditDetails;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasRequirements;
use Filament\Forms\Form;

class CreditSchema
{
    use HasCreditBasicInformation;
    use HasCreditDetails;
    use HasRequirements;

    // Стандартная версия (6 полей) - по умолчанию
    public static function make(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::creditDetails(),
            self::requirements(),
        ]);
    }

    public static function makeSimple(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::simpleCreditDetails(), // 4 поля
        ]);
    }

    public static function makeFull(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::fullCreditDetails(), // 8 полей
            self::requirements(),
        ]);
    }

    public static function makeConfigurable(Form $form, array $config = []): Form
    {
        $schema = [];

        if ($config['showBasicInformation'] ?? true) {
            $schema[] = self::basicInformation();
        }

        $schema[] = self::configurableCreditDetails($config);

        if ($config['showRequirements'] ?? false) {
            $schema[] = self::requirements();
        }

        return $form->schema($schema);
    }
}
