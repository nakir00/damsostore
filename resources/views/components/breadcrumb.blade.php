@props(['breadcrumbs'=>[]])

<div>
    <!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->

        <nav class="mx-auto" aria-label="Breadcrumb">
            <ol class="bg-white font-semibold flex ">
                <li class="flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 text-xs hover:underline hover:text-black" wire:navigate>
                        Accueil
                    </a>
                    <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                        <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                    </svg>
                </li>

                @foreach($breadcrumbs as $breadcrumb)
                    <li class="flex items-center">
                        @if ($loop->first)
                        <a href="{{route('collection',['slug'=>$breadcrumb['slug'],'g'])}}" wire:navigate>
                            <span class="text-gray-600 text-sm hover:underline hover:text-black">
                                {{ $breadcrumb['label'] }}
                            </span>
                        </a>
                        @else
                        <a href="{{route('collection',['slug'=>$breadcrumb['slug']])}}" wire:navigate>
                            <span class="text-gray-600 text-sm hover:underline hover:text-black">
                                {{ $breadcrumb['label'] }}
                            </span>
                        </a>
                        @endif
                        @if (!$loop->last)
                        <svg width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                            <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                          </svg>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>

</div>
