<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Specialties\Components\Schemas;

use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasBasicInformation;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasSpecialtyDetails;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasRequirements;
use Filament\Forms\Form;

class SpecialtySchema
{
    use HasBasicInformation;
    use HasSpecialtyDetails;
    use HasRequirements;

    public static function make(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::specialtyDetails(),
            self::requirements(),
        ]);
    }
}
