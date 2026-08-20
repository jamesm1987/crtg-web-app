<x-filament-panels::page>
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">{{ $this->record->name }}'s Picks</h2>
        <div class="text-sm text-gray-500">
            <p>Total Spent: &pound;{{ $this->getTotalSpent() }}m</p>
            <p>Total Points: <span class="font-bold text-success-600">{{ $this->getTotalPoints() }}</span></p>
        </div>
    </div>

    @forelse ($this->getPicksByCompetition() as $competitionName => $picks)

        <x-filament::section :heading="$competitionName">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                
                @foreach ($picks as $pick)

                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        
                        <div class="flex items-center gap-3">
                            @if ($pick->team->logo_url)
                                <img src="{{ $pick->team->logo_url }}" class="h-8 w-8 object-contain" alt="{{ $pick->team->name }}" />
                            @endif

                            <div>
                                <div class="font-medium">{{ $pick->team->name }}</div>
                            </div>
                        </div>

                        <div class="text-right">

                            <div class="font-bold text-success-600">{{ $pick->team->calculateEarnedPoints() }} pts</div>
                            <div class="text-xs text-gray-500">&pound; {{ number_format($pick->team->price, 0) }}m</div>

                        </div>
                    </div>

                @endforeach

            </div>
        </x-filament::section>

    @empty

        <x-filament::section>
            <p class="text-center text-gray-400">No picks yet.</p>
        </x-filament::section>

    @endforelse
</x-filament-panels::page>
