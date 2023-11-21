 <!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
 @props(['slide'])

 <div {{ $attributes->merge(['class' => 'swiper-slide h-auto relative' ]) }}>

    <img class=" w-full h-full object-cover block" src="{{$slide['url']}}" alt="">
    <div class="z-10 fixed bottom-1/4 right-1/4 rounded " style="background-color: {{$slide['secondary']}}; box-shadow: 0 35px 60px -15px {{$slide['primary']}}ff">

        <div class="p-8 flex flex-col pt-3">
            {!!$slide['info']!!}

            <div class="relative flex items-center justify-center shadow-sm" style="background-color: {{$slide['primary']}};">
                <a href="{{$slide['button_link']}}" class="outline-none transition duration-75 focus:ring-2 rounded-lg gap-1.5 px-3 py-2" wire:navigate>
                    <span class="font-semibold text-sm" style="color: {{$slide['secondary']}};">
                        {{$slide['button_message']}}
                    </span>
                </a>
            </div>

        </div>


    </div>
</div>
{{-- array:7 [▼ // resources\views/components/home-slide.blade.php
  "button_message" => "porter maintenant"
  "button_link" => "-NOIR"
  "primary" => "#000000"
  "secondary" => "#ffffff"
  "position" => "SE"
  "url" => "http://127.0.0.1:8008/storage/media/3f25bba1-3e8d-482f-a32a-a2735ecdd8e0.jpg"
  "info" => "<p>onsonsofuvnsiufvusfvuisqoiovqpouvfnhonv</p>"
] --}}
