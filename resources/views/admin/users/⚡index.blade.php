<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>

    <flux:heading size="xl" class="mb-3">Users</flux:heading>

    <livewire:admin.resource-table
    :model="\App\Models\User::class"
    :columns="['name', 'email', 'created_at']"
    :searchable="['name', 'email']"
    editRoute="admin.users.edit"
    createRoute="admin.users.create"
/>
</div>