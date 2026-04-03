<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
{
    try {
        return Socialite::driver('google')->redirect();
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'password' => bcrypt(str()->random(24)),
            ]
        );

        

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}