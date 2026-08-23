<x-layouts::guest.header :title="$title ?? null">
    <flux:main class="!pt-0 !px-0">
        {{ $slot }}
    </flux:main>
</x-layouts::app.header>
