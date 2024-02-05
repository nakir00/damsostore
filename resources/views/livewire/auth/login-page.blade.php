<div class=" bg-green-100  w-full h-screen flex justify-center items-center" >

    <x-filament::modal id="expired" alignment="center" icon="heroicon-o-check" icon-color="success">
        <x-slot name="heading">
            {{$header}}
        </x-slot>

        <x-slot name="description">
            {{$content}} <br> veuillez demander un autre lien.
        </x-slot>

        <x-slot name="footer">
            <button
                x-on:click="$dispatch('close-modal', { id : 'expired' })"
                class="flex w-full items-center justify-center rounded-md border border-transparent bg-black p-3 font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
            Fermer
        </button>
        </x-slot>
    </x-filament::modal>

    @if (array_key_exists('status',session()->all()))

        <div x-data x-init="$dispatch('open-modal', { id : 'expired' })"></div>
            @php
                session()->forget('status');
            @endphp
    @endif
    <div class="flex flex-col justify-center px-6 py-8 lg:px-8 w-96 rounded-md bg-white drop-shadow-2xl">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm flex flex-col items-center">
          <x-application-logo class="mx-auto w-auto fill-current h-9" />
          <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Connection</h2>
        </div>

        <div class="mt-4 sm:mx-auto sm:w-full">
           <livewire:auth.loginForm />
        </div>
      </div>

</div>
