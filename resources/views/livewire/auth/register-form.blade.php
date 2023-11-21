<div>
    <form class=" space-y-2" wire:submit='register'>
        {{ $this->form }}
        <div>
            <span class=" font-light text-sm text-gray-400"> vous avez un compte ? <a href="{{route('auth.login')}}" class=" text-black font-semibold" wire:navigate> se connecter</a></span>
        </div>
        <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-black px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">S'enregistrer</button>
        </div>
    </form>
</div>
