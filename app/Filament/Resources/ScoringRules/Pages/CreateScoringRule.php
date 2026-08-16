<?php

namespace App\Filament\Resources\ScoringRules\Pages;

use App\Filament\Resources\ScoringRules\ScoringRuleResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Competition;

class CreateScoringRule extends CreateRecord
{
    protected static string $resource = ScoringRuleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['competition_ids']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->category !== 'trophy') {
            return;
        }

        $selectedIds = $this->data['competition_ids'] ?? [];

        if (empty($selectedIds)) {
            return;
        }

        Competition::query()
            ->whereIn('id', $selectedIds)
            ->update(['trophy_scoring_rule_id' => $this->record->id]);
    }
}
