<?php

namespace App\Filament\Resources\UserTeamPicks\Pages;

use App\Filament\Resources\UserTeamPicks\UserTeamPickResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserTeamPick extends CreateRecord
{
    protected static string $resource = UserTeamPickResource::class;
}
