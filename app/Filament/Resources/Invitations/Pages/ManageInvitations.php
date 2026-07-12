<?php

namespace App\Filament\Resources\Invitations\Pages;

use App\Filament\Resources\Invitations\InvitationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Carbon\Carbon;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;

class ManageInvitations extends ManageRecords
{
    protected static string $resource = InvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->using(function (array $data): Invitation {
                    foreach ($data['invites'] as $invite) {
                        $invite = Invitation::create([
                            'name'       => $invite['name'],
                            'email'      => $invite['email'],
                            'expires_at' => now()->addDays(30),
                        ]);

                        Mail::to($invite['email'])->send(new UserInvitation($invite));
                    }
                    
                    return $invite ?? new Invitation();
                }),
        ];
    }
}
