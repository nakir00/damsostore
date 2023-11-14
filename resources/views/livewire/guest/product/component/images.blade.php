<div class="lg:col-span-2 lg:col-start-1 lg:row-start-1 lg:mx-auto lg:grid lg:w-full lg:max-w-7xl lg:grid-cols-2 lg:gap-x-8 lg:px-8">
    <div class="flex flex-col justify-around " x-data x-on:yup.window="console.log(321)">
        @foreach ($images as $image)
            <div class="aspect-h-3 aspect-w-3 hidden overflow-hidden rounded-lg lg:block lg: mb-2">
                <img src="{{$image}}" alt="Model wearing plain black basic tee." class="h-full w-full object-cover object-center">
            </div>
            @endforeach
        {{-- <div class="aspect-h-5 aspect-w-4 lg:aspect-h-3 lg:aspect-w-3 sm:overflow-hidden sm:rounded-lg lg: mb-2">
            <img src="https://tailwindui.com/img/ecommerce-images/product-page-02-featured-product-shot.jpg" alt="Model wearing plain white basic tee." class="h-full w-full object-cover object-center">
        </div> --}}
    </div>
</div>
