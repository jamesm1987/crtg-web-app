<?php

namespace App\Filament\Resources\UserTeamPicks\Pages;

use App\Filament\Resources\UserTeamPicks\UserTeamPickResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserTeamPick extends EditRecord
{
    protected static string $resource = UserTeamPickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
