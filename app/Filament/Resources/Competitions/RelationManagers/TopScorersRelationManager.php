<?php

namespace App\Filament\Resources\Competitions\RelationManagers;

use App\Filament\Resources\Competitions\CompetitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Enums\CompetitionType;
use App\Jobs\SyncGoalscorersJob;

use App\Filament\Resources\Competitions\Resources\TopScorers\TopScorerResource;

class TopScorersRelationManager extends RelationManager
{
    protected static string $relationship = 'topScorers';

    protected static ?string $relatedResource = TopScorerResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                Action::make('syncGoalscorers')
                ->label('Sync goalscorers')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Sync goalscorers from API')
                ->modalDescription(fn () => "This will pull all goal scorers for {$this->getOwnerRecord()->name} from API-Football.")
                ->action(function () {
                    SyncGoalscorersJob::dispatch($this->getOwnerRecord());

                    Notification::make()
                        ->title('Sync queued')
                        ->body('Goalscorers are being pulled from API-Football.')
                        ->success()
                        ->send();
                }),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === CompetitionType::League->getLabel();
    }
}
