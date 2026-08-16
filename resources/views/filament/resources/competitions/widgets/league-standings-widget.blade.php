<x-filament-widgets::widget>
    <x-filament::section heading="League Table">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <th class="py-2 pr-4 text-left w-8">#</th>
                        <th class="py-2 pr-4 text-left">Team</th>
                        <th class="py-2 px-3 text-center">P</th>
                        <th class="py-2 px-3 text-center">W</th>
                        <th class="py-2 px-3 text-center">D</th>
                        <th class="py-2 px-3 text-center">L</th>
                        <th class="py-2 px-3 text-center">GF</th>
                        <th class="py-2 px-3 text-center">GA</th>
                        <th class="py-2 px-3 text-center">GD</th>
                        <th class="py-2 px-3 text-center font-bold">Pts</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($this->getStandings() as $index => $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="py-2 pr-4 text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-2 pr-4">
                                <div class="flex items-center gap-2">
                                    @if($row['team']->logo_url)
                                        <img class="h-6 w-6 flex-shrink-0 object-contain" width="25" height="25" src="{{ $row['team']->logo_url }}" />
                                    @endif
                                    <span class="font-medium">{{ $row['team']->name }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-3 text-center text-gray-600 dark:text-gray-400">{{ $row['played'] }}</td>
                            <td class="py-2 px-3 text-center text-gray-600 dark:text-gray-400">{{ $row['won'] }}</td>
                            <td class="py-2 px-3 text-center text-gray-600 dark:text-gray-400">{{ $row['drawn'] }}</td>
                            <td class="py-2 px-3 text-center text-gray-600 dark:text-gray-400">{{ $row['lost'] }}</td>
                            <td class="py-2 px-3 text-center text-gray-600 dark:text-gray-400">{{ $row['gf'] }}</td>
                            <td class="py-2 px-3 text-center text-gray-600 dark:text-gray-400">{{ $row['ga'] }}</td>
                            <td class="py-2 px-3 text-center text-gray-600 dark:text-gray-400">{{ $row['gd'] }}</td>
                            <td class="py-2 px-3 text-center font-bold">{{ $row['points'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>