<x-layouts::app :title="__('Users')">
    <div class="p-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-dark mb-6">Users</h1>

        <livewire:resource-table 
            model="App\Models\User" 
            :columns="['name', 'email', 'created_at']" 
        />
    </div>
</x-layouts::app>