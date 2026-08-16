<?php

namespace App\Filament\Resources\Competitions\Pages;

use App\Enums\CompetitionType;
use App\Filament\Resources\Competitions\CompetitionResource;
use App\Filament\Resources\Competitions\Widgets\LeagueStandingsWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCompetition extends ViewRecord
{
    protected static string $resource = CompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            LeagueStandingsWidget::class,
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }

    protected function getFooterWidgetsData(): array
    {
        return [
            LeagueStandingsWidget::class => [
                'record' => $this->record,
            ],
        ];
    }
}
