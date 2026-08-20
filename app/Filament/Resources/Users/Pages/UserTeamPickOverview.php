<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\UserTeamPick;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class UserTeamPickOverview extends Page
{
    use InteractsWithRecord;

    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.users.pages.user-team-pick-overview';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getPicksByCompetition(): Collection
    {
        $record = $this->getRecord();

        return $record
            ->teamPicks()
            ->active()
            ->with(['team', 'competition'])
            ->get()
            ->groupBy(fn (UserTeamPick $pick): string => $pick->competition->name);
    }

    public function transfers()
    {
        // TODO: Implement transfers logic
    }
    
    public function getTotalPoints(): int
    {        
        return (int) $this->getPicksByCompetition()
            ->flatten()
            ->sum(fn (UserTeamPick $pick): int => $pick->team->calculateEarnedPoints());
    }

    public function getTotalSpent(): int
    {
        return (int) $this->getPicksByCompetition()
            ->flatten()
            ->sum(fn (UserTeamPick $pick): int => $pick->team->price);
    }
}
