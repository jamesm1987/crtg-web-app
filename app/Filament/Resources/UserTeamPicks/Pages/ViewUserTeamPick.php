<?php

namespace App\Filament\Resources\UserTeamPicks\Pages;

use App\Filament\Resources\UserTeamPicks\UserTeamPickResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUserTeamPick extends ViewRecord
{
    protected static string $resource = UserTeamPickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
