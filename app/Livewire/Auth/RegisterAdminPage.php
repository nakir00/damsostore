<?php

namespace App\Livewire\Auth;

use App\Models\RegisterToken;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
class RegisterAdminPage extends Component
{

    public ?RegisterToken $registerToken=null;

    public function mount($token)
    {
        $this->registerToken=RegisterToken::query()->where('token',$token)->get()->first();
        if(Carbon::parse($this->registerToken->expires_at)->lt(Carbon::now()))
        {
            Session::put('status','expired');
            return redirect(route('auth.login'));
        }
        if($this->registerToken->consumed_at)
        {
            Session::put('status','consumed');
            return redirect(route('auth.login'));
        }
        if($this->registerToken===null)
        {
            return redirect(route('auth.login'));
        }

    }

    #[Title('page de connexion')]
    public function render()
    {
        return view('livewire.auth.register-admin-page')->with([
            'registerData'=>$this->registerToken
        ]);
    }

}
