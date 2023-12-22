<div>
    @livewire('notifications')


    <div class="hidden  md:flex relative isolate overflow-hidden bg-white px-6 py-24 sm:py-16 md:overflow-visible md:px-0">

        <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 md:mx-0 md:max-w-none md:grid-cols-2 md:items-start md:gap-y-10">

            <livewire:guest.product.component.images :$images/>

            <div class=" -ml-12 -mr-12 md:mr-0 -mt-12 p-12 h-screen md:sticky md:top-4 md:col-start-2 md:row-span-2 md:row-start-1 md:overflow-hidden">
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

    <div class="md:hidden lg:hidden">
        <livewire:guest.product.component.mobile-swiper :$images :$form/>
    </div>

</div>
