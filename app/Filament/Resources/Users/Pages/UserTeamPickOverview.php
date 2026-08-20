<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use App\Models\UserTeamPick;

class UserTeamPickOverview extends Page
{
    use InteractsWithRecord;

    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.users.pages.user-team-pick-overview';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getPicksByCompetition()
    {
        return $this->record
            ->teamPicks()
            ->active()
            ->with('team', 'competition')
            ->get()
            ->groupBy(fn (UserTeamPick $pick) => $pick->competition->name);
    }

    public function transfers() {

    }
    
    public function getTotalPoints(): int
    {        
        return $this->getPicksByCompetition()
            ->flatten()
            ->sum(fn (UserTeamPick $pick) => $pick->calculateEarnedPoints());
    }

    public function getTotalSpent(): int
    {
        return $this->getPicksByCompetition()
            ->flatten()
            ->sum(fn (UserTeamPick $pick) => $pick->team->price);
    }
}
