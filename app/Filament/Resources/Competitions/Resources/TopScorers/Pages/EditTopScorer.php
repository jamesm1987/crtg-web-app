<?php

namespace App\Filament\Resources\Competitions\Resources\TopScorers\Pages;

use App\Filament\Resources\Competitions\Resources\TopScorers\TopScorerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTopScorer extends EditRecord
{
    protected static string $resource = TopScorerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
