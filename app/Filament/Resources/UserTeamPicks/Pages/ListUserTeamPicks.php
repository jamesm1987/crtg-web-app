<?php

namespace App\Filament\Resources\UserTeamPicks\Pages;

use App\Filament\Resources\UserTeamPicks\UserTeamPickResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserTeamPicks extends ListRecords
{
    protected static string $resource = UserTeamPickResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
