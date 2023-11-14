<div class="mt-20 relative ">
    {{-- Stop trying to control. --}}
    <div class=" mx-32 flex flex-col justify-between h-16">

        <x-breadcrumb :$breadcrumbs />

        <span class="text-4xl">{{$name}}</span>
    </div>

    <livewire:guest.collection.component.collection-slider  :$collections />


    <div class=" relative mx-32  my-16">
        <livewire:guest.collection.component.grid-list :products="$products->items()"/>
    </div>

    <div class="flex flex-row justify-center items-center">
        {{$products->links()}}
    {{--     <div class="pagination">
            <ul>
                @if($products->onFirstPage())
                    <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true">&lsaquo;</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $products->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif --}}

                {{-- Pagination Elements --}}
                {{-- @for($i=1;$i<=$products->lastPage();$i++)
                {
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif --}}

                    {{-- Array Of Links --}}
                  {{--   @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $products->currentPage())
                                <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                }
                @endfor --}}
                {{-- Next Page Link --}}
                {{-- @if ($products->hasMorePages())
                    <li>
                        <a href="{{ $products->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span aria-hidden="true">&rsaquo;</span>
                    </li>
                @endif
            </ul>
        </div> --}}
    </div>



</div>
