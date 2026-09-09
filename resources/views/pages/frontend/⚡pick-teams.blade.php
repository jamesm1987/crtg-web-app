<?php

use App\Models\Competition;
use App\Models\Team;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Number;

new #[Title('Pick Teams'), Layout('layouts.app')] class extends Component {
    
    public ?int $activeLeagueId = null;

    public array $selectedTeams = [];

    public ?int $maxTeamsPerLeague = null;

    public ?int $leagueCount = null;

    public ?int $budget = null;

    public function mount(): void
    {
        $this->activeLeagueId = $this->leagues->first()?->id;

        $this->maxTeamsPerLeague = Setting::get('teams_per_league');

        $this->leagueCount = $this->leagues->count();

        $this->budget = Setting::get('budget');
        
        $this->selectedTeams = $this->leagues->pluck('id')
            ->mapWithKeys(fn (int $id) => [$id => []])
            ->all();
    }

    #[Computed]
    public function leagues(): Collection
    {
        return Competition::query()
            ->where('type', 'league')
            ->get();
    }

    #[Computed]
    public function teams(): Collection
    {
        if (! $this->activeLeagueId) {
            return new Collection();
        }

        return Team::query()
            ->where('competition_id', $this->activeLeagueId)
            ->get();
    }

    public function spent(): int
    {
        $allSelectedIds = array_merge(...array_values($this->selectedTeams));
    
        if (empty($allSelectedIds)) {
            return 0;
        }
    
        return (int) Team::query()
            ->whereIn('id', $allSelectedIds)
            ->sum('price');
    }
    
    public function remainingBudget(): int
    {
        return $this->budget - $this->spent();
    }

    public function toggleTeam(int $id): void
    {
        $team = $this->teams->firstWhere('id', $id);

        if (! $team) {
            return;
        }

        $current = $this->selectedTeams[$this->activeLeagueId] ?? [];
        
        // Unselect team
        if (in_array($id, $current)) {
            $this->selectedTeams[$this->activeLeagueId] = array_values(array_diff($current, [$id]));
            return;
        }
    
        if (count($current) >= $this->maxTeamsPerLeague) {
            return;
        }

        if ($team->price > $this->remainingBudget()) {
            return;
        }
    
        $this->selectedTeams[$this->activeLeagueId][] = $id;
    }

    public function setLeague(int $leagueId): void
    {
        $this->activeLeagueId = $leagueId;
    }

    public function save()
    {
        return $this->redirect('/dashboard', navigate: true);
    }

}; ?>

<section aria-labelledby="pick-teams-heading">
    <header>
        <flux:heading id="pick-teams-heading" level="1" size="xl">Pick your teams</flux:heading>
        
        <flux:subheading class="mt-2">
            <span class="capitalize">{{ Number::spell($this->maxTeamsPerLeague) }}</span> teams from each league
        </flux:subheading>
    </header>

    <nav aria-label="Leagues">
        <ul role="list" class="flex gap-3 mt-3">
            @foreach ($this->leagues as $league)
                <li wire:key="league-item-{{ $league->id }}">
                    <button 
                        type="button"
                        x-on:click="$wire.activeLeagueId !== {{ $league->id }} && $wire.setLeague({{ $league->id }})"
                        @class([
                            'rounded-md border px-4 py-2 text-sm font-bold transition-colors',
                            'border-pulse bg-pulse text-pulse-foreground' => $league->id === $this->activeLeagueId,
                            'border-border bg-surface text-muted-foreground hover:text-foreground' => $league->id !== $this->activeLeagueId,
                        ])
                        aria-pressed="{{ $league->id === $this->activeLeagueId ? 'true' : 'false' }}"
                    >
                        {{ $league->name }}
                    </button>
                </li>
            @endforeach
        </ul>
    </nav>

    

    <section aria-label="Teams" class="mt-6 max-w-4xl">

        <!-- Real Teams Grid -->
        <ul 
            role="list" 
            wire:loading.delay.remove 
            wire:target="setLeague" 
            class="grid grid-cols-1 gap-4 sm:grid-cols-2"
        >
            @forelse ($this->teams as $team)
                @php 
                    $isSelected = in_array($team->id, $this->selectedTeams[$this->activeLeagueId] ?? [], true); 
                    $selectedCount = count($this->selectedTeams[$this->activeLeagueId] ?? []);
                    $leagueFull = ! $isSelected && $selectedCount >= $this->maxTeamsPerLeague;
                    $isDisabled = $leagueFull || (! $isSelected && $team->price > $this->remainingBudget());
                @endphp
                
                <li wire:key="team-item-{{ $team->id }}">
                    <button
                        type="button"
                        wire:click="toggleTeam({{ $team->id }})" 
                        wire:loading.attr="disabled"
                        wire:target="toggleTeam({{ $team->id }})"
                        aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                        @disabled($isDisabled)
                        @class([
                            'flex w-full items-center justify-between rounded-lg border px-4 py-8 shadow-sm transition-all text-left',
                            'border-pulse bg-pulse/5' => $isSelected,
                            'border-border' => ! $isSelected,
                            'disabled:opacity-50' => $isDisabled,
                            'hover:border-pulse' => !$isDisabled
                        ])
                    >
                        <span class="font-medium text-foreground flex items-center">
                            <img class="w-5 h-5 me-2" src="{{ $team->logo_url }}" alt="" aria-hidden="true" />
                            <span>{{ $team->name }}</span>
                        </span>

                        <span class="flex items-center gap-2">
                            <span class="text-sm font-bold">{{ $team->formatted_price }}</span>
                            
                            <span 
                                @class([
                                    'text-sm font-thin rounded-full w-6 h-6 flex items-center justify-center',
                                    'bg-pulse text-pulse-foreground' => $isSelected,
                                    'text-gray-500 bg-gray-300' => ! $isSelected
                                ])
                                aria-hidden="true"
                            >
                                @if ($isSelected)
                                    <flux:icon.check class="w-3 h-3 text-white" />
                                @else
                                    <flux:icon.plus class="w-3 h-3" />
                                @endif
                            </span>
                        </span>
                    </button>
                </li>
            @empty
                <li class="col-span-full py-6 text-center text-muted-foreground">
                    No teams available for this league.
                </li>
            @endforelse
        </ul>

        <aside class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <flux:icon.wallet class="w-3 h-3 text-pulse" aria-hidden="true" />
                <p class="uppercase">Budget</p>
            </div>
        </aside>
    </section>

</section>

