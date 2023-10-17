<div class="px-8 py-8 mb-4 bg-white border rounded-xl">

    <form wire:submit="create">
        {{ $this->form }}

        <button type="submit">
            Submit
        </button>
    </form>

    <x-filament-actions::modals />

</div>
