<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Credits\Components\Schemas;

use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditBasicInformation;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCreditDetails;
use Filament\Forms\Form;

class SimpleCreditSchema
{
    use HasCreditBasicInformation;
    use HasCreditDetails;

    public static function make(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::simpleCreditDetails(),
        ]);
    }
}
