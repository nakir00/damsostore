<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Provider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite as FacadesSocialite;

class Socialite extends Controller
{
    public function redirect(Provider $provider)
    {
        return FacadesSocialite::driver($provider->value)->redirect();
    }

    public function authenticate(Provider $provider)
    {
        try {

            $user = FacadesSocialite::driver($provider->value)->user();

            $finduser = User::where('social_id', $user->id)->first();

            if($finduser){
                Auth::login($finduser);
                if(auth()->user()->role==="client")
                {
                    return redirect(route('client.dashboard'));
                }
                return redirect(route('filament.admin.pages.dashboard'));
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id'=> $user->id,
                    'social_type'=> $provider->value,
                    'password' => $provider->value
                ]);

                Auth::login($newUser);

                if(Auth::user()->role==='admin')
                {
                    return redirect(route('filament.admin.pages.dashboard'));
                }
                return redirect(route('client.dashboard'));
            }

        } catch (Exception $e) {
            dd($e->getMessage(),'error');
        }
    }
}
