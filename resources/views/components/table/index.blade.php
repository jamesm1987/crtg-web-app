<div class="rounded-lg border border-zinc-700 overflow-hidden">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left border-collapse']) }}>
        <thead class="bg-zinc-800 text-zinc-400 text-xs uppercase tracking-wide">
            <tr>
                {{ $header }}
            </tr>
        </thead>

        <tbody classs="divide-y divide-zinc-700">
            {{ $body }}
        </tbody>
    </table>
</div>