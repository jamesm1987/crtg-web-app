@props([
    'sortable' => false,
    'direction' => null    
])

<th scope="col" {{ $attributes->merge(['class' => 'px-4 py-3'])->class(['p-0' => $sortable]) }}>
    @if (!$sortable)
        {{ $slot }}
    @else

        <button type="button" class="group flex items-center gap-1 w-full h-full px-4 py-3 text-left hover:text-white transition-colors focus:outline-none">
            <span>{{ $slot }}</span>

            <span class="transition-opacity" aria-hidden="true">
                
                @if ($direction === 'asc')
                    <span>↑</span>
                
                @elseif ($direction === 'desc')

                    <span>↓</span>
                
                @else

                    <span class="opacity-0 group-hover:opacity-30">↕</span>
                
                @endif
                    
            </span>
        
        </button>
    
    @endif
</th>

