<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use App\Filament\Resources\Invitations\InvitationResource;

use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('invite')
                ->label('Invite User')
                ->icon('heroicon-o-envelope')
                ->url(fn (): string => InvitationResource::getUrl('index'))
                ->button(),
        ];
    }
}
