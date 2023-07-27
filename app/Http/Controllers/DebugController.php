<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DefaultEmail;
use App\User;
use Laravel\Socialite\Facades\Socialite;

class DebugController extends Controller
{
    public function handler($method)
    {
        return $this->$method();
    }

    protected function userActivation()
    {
        $user = User::first();
        $email = new DefaultEmail([
            'subject' => "Ativação de conta",
            'view' => "emails.user_activation",
            'with' => [
                'firstName' => $user->firstName,
                'activationLink' => $user->activationLink
            ]
        ]);
        return $email->render();
    }

    protected function socialite()
    {
        return Socialite::driver('github')->redirect();
    }
}
