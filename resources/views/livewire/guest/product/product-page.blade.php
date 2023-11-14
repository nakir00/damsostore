<div>
    <div class="relative isolate overflow-hidden bg-white px-6 py-24 sm:py-16 lg:overflow-visible lg:px-0">

        <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2 lg:items-start lg:gap-y-10">
{{-- @php
    dd($this->product);
@endphp --}}
            <livewire:guest.product.component.images :$images/>

            <div class=" -ml-12 -mr-12 lg:mr-0 -mt-12 p-12 h-screen lg:sticky lg:top-4 lg:col-start-2 lg:row-span-2 lg:row-start-1 lg:overflow-hidden">
                    <!-- Options -->
                    <livewire:guest.product.component.form :$form />
            </div>
        </div>
        {{-- <div class="px-8">

            <livewire:guest.welcome.component.product-slider />

            <livewire:guest.welcome.component.product-slider />

            <livewire:guest.collection.component.collection-slider />
        </div> --}}

    </div>

</div>
