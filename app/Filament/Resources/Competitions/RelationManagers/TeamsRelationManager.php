<?php

namespace App\Filament\Resources\Competitions\RelationManagers;

use App\Filament\Resources\Competitions\CompetitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Jobs\SyncTeamsJob;
use App\Enums\CompetitionType;

use Filament\Actions\ImportAction;
use App\Filament\Imports\TeamImporter;
use Illuminate\Validation\Rules\File;

use App\Filament\Resources\Competitions\Resources\Teams\TeamResource;

class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';

    protected static ?string $relatedResource = TeamResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn($query) => $query->withSum('pointLedger', 'points')
            )
            ->headerActions([
                CreateAction::make(),
                Action::make('syncTeams')
                ->label('Sync teams')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Sync teams from API')
                ->modalDescription(fn () => "This will pull all teams for {$this->getOwnerRecord()->name} from API-Football.")
                ->action(function () {
                    SyncTeamsJob::dispatch($this->getOwnerRecord());

                    Notification::make()
                        ->title('Sync queued')
                        ->body('Teams are being pulled from API-Football.')
                        ->success()
                        ->send();
                }),
                ImportAction::make()
                ->importer(
                    TeamImporter::class
                )
                ->fileRules([
                    File::types(['csv','xlsx'])
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === CompetitionType::League->getLabel();
    }
}
