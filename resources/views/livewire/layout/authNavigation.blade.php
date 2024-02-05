<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <header class="bg-white">
        <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="">
            <div class="flex h-16 justify-center lg:justify-between items-center">
                <a  href="{{ route('home') }}"  >
                    <div class="flex items-center shrink-0">
                        <x-application-logo class="block w-auto text-gray-800 fill-current h-9 " />
                        <span class="self-center whitespace-nowrap  ml-3">DamsoStore</span>
                    </div>
                    
                </a>
            </div>
          </div>
        </nav>
    </header>
</div>
