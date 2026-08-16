<?php

namespace App\Filament\Resources\ScoringRules\Pages;

use App\Filament\Resources\ScoringRules\ScoringRuleResource;
use App\Models\Competition;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScoringRule extends EditRecord
{
    protected static string $resource = ScoringRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->category === 'trophy') {
            $data['competition_ids'] = $this->record
                ->competitions()
                ->pluck('id')
                ->toArray();
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['competition_ids']);

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->category !== 'trophy') {
            return;
        }

        $selectedIds = $this->data['competition_ids'] ?? [];

        // Unlink any competitions previously pointing here but now deselected
        Competition::query()
            ->where('trophy_scoring_rule_id', $this->record->id)
            ->whereNotIn('id', $selectedIds)
            ->update(['trophy_scoring_rule_id' => null]);

        // Link the currently selected ones
        if (! empty($selectedIds)) {
            Competition::query()
                ->whereIn('id', $selectedIds)
                ->update(['trophy_scoring_rule_id' => $this->record->id]);
        }
    }
}