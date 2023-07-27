<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DefaultEmail;
use App\User;
use Illuminate\Support\Facades\Mail;

class DebugController extends Controller
{
    public function emailTest($method)
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
        // Mail::to('bassalobre.vinicius@gmail.con')->send($email);
        return $email->render();
    }
}
