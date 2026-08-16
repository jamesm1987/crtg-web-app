<?php

namespace App\Filament\Resources\TeamPointsLedgers\Pages;

use App\Filament\Resources\TeamPointsLedgers\TeamPointsLedgerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeamPointsLedgers extends ListRecords
{
    protected static string $resource = TeamPointsLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
