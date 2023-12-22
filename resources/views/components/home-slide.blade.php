 <!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
 @props(['slide'])

 @php
    $position=match ($slide['position']) {
         'center'=> "top-1/2 right-1/2",
         'N' => "top-1/4 right-1/2",
         'E' => "top-1/2 right-1/4",
         'W' => "top-1/2 right-3/4",
         'S' => "top-3/4 right-1/2",
         'NE' => "top-1/4 right-1/4",
         'NW' => "top-1/4 right-3/4",
         'SE' => "top-3/4 right-1/4",
         'SW' => "top-3/4 right-3/4",
    };
    $fontSize="font-semibold text-sm";
    if (($slide['info']===null)&&($slide['button_message']!==null)) {
        $fontSize=" font-bold text-3xl";
    }
 @endphp

 <div {{ $attributes->merge(['class' => 'swiper-slide h-auto relative' ]) }}>

    @if ($slide['button_message']===null)<a href="{{$slide['button_link']}}">@endif
    <img class=" w-full h-full object-cover block" src="{{$slide['url']}}" alt="">
    @if($slide['button_message']!==null)


        <div class="z-10 fixed {{$position}} " >

                @if ($slide['info']!==null)
                    <div class="rounded" style="background-color: {{$slide['secondary']}}; box-shadow: 0 35px 60px -15px {{$slide['primary']}}ff">

                        <div class="p-8 flex flex-col pt-3">
                            {!!$slide['info']!!}
                            jommlaaa
                            <div class="relative flex rounded items-center justify-center shadow-sm" style="background-color: {{$slide['primary']}};">
                @endif



                                <a href="{{$slide['button_link']}}" class="outline-none transition duration-75 focus:ring-2 rounded-lg gap-1.5 px-3 py-2">
                                    <span class="{{$fontSize}}" style="color: {{$slide['secondary']}};">
                                        {{$slide['button_message']}}
                                    </span>
                                </a>


                @if ($slide['info']!==null)
                            </div>
                        </div>
                    </div>
                @endif
        </div>
    @endif
    @if ($slide['button_message']===null)</a>@endif
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
