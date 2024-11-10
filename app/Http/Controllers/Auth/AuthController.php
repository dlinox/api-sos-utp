<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Mail\Auth\SignUpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function signIn(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = $this->user->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        if ($user->status == 0) {
            return response()->json([
                'message' => 'Usuario inactivo'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'full_name' => $user->name . ' ' . $user->paternal_surname . ' ' . $user->maternal_surname,
                'email' => $user->email,
                'role' => $user->role,
                'redirect_route' => $user->role == 'admin' ? '/a' : '/u',
            ],
            'permissions' => $user->role,
        ]);
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function user(Request $request)
    {
        $user =  $request->user();

        $redirect_route = $user->role == 'admin' ? '/a' : '/u';

        return response()->json([
            'user' => [
                'id' => $user->id,
                'full_name' => $user->name . ' ' . $user->paternal_surname . ' ' . $user->maternal_surname,
                'role' => $user->role,
                'email' => $user->email,
                'redirect_route' => $redirect_route,
            ],
            'permissions' => $user->role,
        ]);
    }

    public function signUp(Request $request)
    {

        try {

            $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

            $data['document_number'] = $request->documentNumber;
            $data['name'] = $request->name;
            $data['paternal_surname'] = $request->paternalSurname;
            $data['maternal_surname'] = $request->maternalSurname;
            $data['email'] = $request->email;
            $data['phone_number'] = $request->phoneNumber;
            $data['password'] = $password;
            $data['role'] = 'user';
            $data['status'] = 1;
            $user = $this->user->create($data);
            if ($user) {
                Mail::to($user->email)->send(new SignUpMail($password));
            }

            return ApiResponse::success($user, 'User created successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
