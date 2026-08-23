<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;


new #[Title('Dashboard'), Layout('layouts.app')] class extends Component {


    public function mount()
    {
    }

}; ?>

<section class="w-full">


    <flux:heading>
        Dashboard
    </flux:heading>

</section>