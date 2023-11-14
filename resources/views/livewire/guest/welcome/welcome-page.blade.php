<div>
    <div class=" max-w-full overflow-x-auto " >

        <div class="relative h-screen w-auto" >
            <livewire:guest.welcome.component.top-slider :$slides/>
            {{-- <livewire:guest.welcome.component.info-Slider /> --}}
        </div>
        
       <div class="my-12">
            <livewire:guest.welcome.component.collection-slider :$collections/>
       </div>


       <div class="mx-16">

             <livewire:guest.welcome.component.sliders :collections="$list"  />

       </div>

    </div>
</div>
