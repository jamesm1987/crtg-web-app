<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


new #[Title('Redeem Invitation'), Layout('layouts.auth')] class extends Component {
    
    public Invitation $invitation;
    public $password = '';

    public function mount(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function save()
    {
        if ($this->invitation->hasExpired()) {
            return;
        }

        $this->validate([
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name'     => $this->invitation->name,
            'email'    => $this->invitation->email,
            'role'     => 'user',
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        return $this->redirect('/dashboard', navigate: true);
    }

}; ?>

<section class="w-full">

    @if ($this->invitation->hasExpired())

    <flux:heading>
        {{ __('Invitation Expired') }}
    </flux:heading>

    <p>
        The invitation for {{ $this->invitation->email }} has expired. 
        Please contact the admin for a new one.
    </p>

    @else 

    <div class="flex flex-col gap-6">

        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Redeem invitation') }}</flux:heading>

                <flux:subheading>{{ __('Create a password to create your account and login') }}</flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Password')" type="password" viewable />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
    

                <flux:button variant="primary" wire:click="save">
                    {{ __('Save password') }}
                </flux:button>
            </div>
        </div>

    </div>


    @endif

</section>