<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->content }}

        <div class="mt-6 flex justify-start">
            <x-filament::button type="submit" size="lg">
                Save Settings   
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>