<div class="mt-20 relative ">
    @livewire('notifications')
    {{-- Stop trying to control. --}}
    <div class=" mx-4 md:mx-10 lg:mx-32 flex flex-col justify-around">

        <x-breadcrumb :$breadcrumbs />

        <span class="text-4xl flex-row my-5">
            {{$name}}
            @if ($discount)
                    <span class="text-black font-semibold py-4 mx-2 whitespace-nowrap text-base" > - {{number_format((int)$discount['data']['percentage']??(int)$discount['data']['fixed_values'], 0, ',', ' . ')}} @if($discount['data']['type']==='percentage')%@else f cfa @endif sur  cette collection !</span>
            @endif
        </span>

        <livewire:guest.collection.component.collects   :$collections />
    </div>

    <div class=" relative mx-4 md:mx-12 lg:mx-32  my-4">
        <livewire:guest.collection.component.grid-list :products="$products->items()"/>
    </div>

</div>
