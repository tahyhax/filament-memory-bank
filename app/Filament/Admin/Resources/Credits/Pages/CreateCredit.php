<?php

namespace App\Filament\Admin\Resources\Credits\Pages;

use App\Filament\Admin\Resources\Credits\CreditResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCredit extends CreateRecord
{
    protected static string $resource = CreditResource::class;
}
