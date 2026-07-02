@props(['colspan'])

<tr>
    <td colspan="{{ $colspan }}" {{ $attributes->merge(['class' => 'px-4 py-16 text-center text-zinc-500']) }}>
        {{ $slot->isEmpty() ? 'No records found.' : $slot }}
    </td>
</tr>