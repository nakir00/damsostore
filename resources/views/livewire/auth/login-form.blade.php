<div>

    <form class="space-y-2" wire:submit='create'>
        {{ $this->form }}

        <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-black px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">Se connecter</button>
        </div>
    </form>
    <div>
        <span class=" font-light text-sm text-gray-400"> vous n'etes pas enrgistré ? <a href="{{route('auth.register')}}" class=" text-black font-semibold hover:underline   "> Inscription</a></span>
    </div>
    <div class="flex w-full justify-center text-sm font-semibold">
        <span>ou</span>
    </div>
    <div class="grid grid-cols-1 mt-2 gap-4">
        <a class="border-black border p-2  flex flex-row justify-evenly rounded-md" href="{{route('auth.socialite.redirect',['provider'=> 'google'])}}" role="button" style="text-transform:none">
            <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="{{asset('assets/google.svg')}}" />
                Se connecter avec Google
          </a>
        {{-- <a class="border-black border p-2  flex flex-row justify-evenly rounded-md" href="" role="button" style="text-transform:none">
            <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/1/1b/Apple_logo_grey.svg" />
            Apple
          </a> --}}
       </div>

</div>
