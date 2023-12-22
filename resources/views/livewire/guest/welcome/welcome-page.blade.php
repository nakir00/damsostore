<div>
    @livewire('notifications')

    <div class=" max-w-full overflow-x-auto " >

        <div class="relative h-screen w-auto" >
            <livewire:guest.welcome.component.top-slider :$slides/>
            {{-- <livewire:guest.welcome.component.info-Slider /> --}}
        </div>
        <x-filament::modal id="commanded" alignment="center" icon="heroicon-o-check" icon-color="success">
            <x-slot name="heading">
                Accusé de reception
            </x-slot>

            <x-slot name="description">
                nous avons reçu votre commande, nous vous communiquerons les frais de livraisons sous peu. <br> A tout de suite !
            </x-slot>

            <x-slot name="footer">
                <button
                    x-on:click="$dispatch('close-modal', { id : 'commanded' })"
                    class="flex w-full items-center justify-center rounded-md border border-transparent bg-black p-3 font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                Fermer
            </button>
            </x-slot>
        </x-filament::modal>
        @if (array_key_exists('statuts',session()->all()))
        <div x-data x-init="$dispatch('open-modal', { id : 'commanded' })"></div>
            @php
                session()->forget('statuts');
                $this->dispatch('emptyCart');
            @endphp
        @endif
       <div class="my-12">
        <livewire:guest.collection.component.collects :$collections />
       </div>

       <div class=" lg:mx-16">
             <livewire:guest.welcome.component.sliders :collections="$list"  />
       </div>

    </div>
</div>
