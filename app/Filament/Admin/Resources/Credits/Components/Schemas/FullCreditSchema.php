<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits\Components\Schemas;

use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditBasicInformation;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditDetails;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasRequirements;
use Filament\Forms\Form;

class FullCreditSchema
{
    use HasCreditBasicInformation;
    use HasCreditDetails;
    use HasRequirements;

    public static function make(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::fullCreditDetails(),
            self::requirements(),
        ]);
    }
}
