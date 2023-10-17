<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <x-box>
        <form wire:submit="createMarque">
            {{-- <x-forms.filepond
                wire:model="images"
                multiple
                allowImagePreview
                imagePreviewMaxHeight="200"
                allowFileTypeValidation
                acceptedFileTypes="['image/png', 'image/jpg']"
                allowFileSizeValidation
                maxFileSize="4mb"

            /> --}}
            {{ $this->form }}

            <button type="submit">
                Submit
            </button>


        </form>

        <x-filament-actions::modals />
    </x-box>

</div>
