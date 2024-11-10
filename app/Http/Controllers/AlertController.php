<?php

namespace App\Http\Controllers;

use App\Mail\AlertMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AlertController extends Controller
{

    public function sendAlert(Request $request)
    {

        $currentUser = Auth::user();
        $users = User::where('id', '!=', $currentUser->id)->get();

        //enviar AlertMail a todos los usuarios
        foreach ($users as $user) {

            Mail::to($user->email)->send(new AlertMail());
        }

        return response()->json([
            'message' => 'Alerta enviada a todos los usuarios'
        ]);
    }
}
