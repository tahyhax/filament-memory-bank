<?php

namespace App\Filament\Admin\Resources\Credits\Pages;

use App\Filament\Admin\Resources\Credits\CreditResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCredits extends ListRecords
{
    protected static string $resource = CreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
