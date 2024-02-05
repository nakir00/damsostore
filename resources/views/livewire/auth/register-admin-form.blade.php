<div>
    <form class=""  wire:submit="submit">
        {{ $this->form }}

        <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-black px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"> Continuer </button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
