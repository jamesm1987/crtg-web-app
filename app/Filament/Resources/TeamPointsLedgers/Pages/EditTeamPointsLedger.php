<?php

namespace App\Filament\Resources\TeamPointsLedgers\Pages;

use App\Filament\Resources\TeamPointsLedgers\TeamPointsLedgerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeamPointsLedger extends EditRecord
{
    protected static string $resource = TeamPointsLedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
