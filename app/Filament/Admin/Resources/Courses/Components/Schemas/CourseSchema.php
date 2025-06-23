<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Courses\Components\Schemas;

use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasBasicInformation;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasCourseDetails;
use App\Filament\Admin\Resources\Shared\Schemas\Traits\HasScheduleFields;
use Filament\Forms\Form;

class CourseSchema
{
    use HasBasicInformation;
    use HasCourseDetails;
    use HasScheduleFields;

    public static function make(Form $form): Form
    {
        return $form->schema([
            self::basicInformation(),
            self::courseDetails(),
            self::scheduleFields(),
        ]);
    }
} 