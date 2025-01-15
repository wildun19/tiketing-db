<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json([
            'status'=>'status',
            'message'=>'User Created successfully',
            'data'=>$user,
        ], 201);
    }

    function loginGoogle (Request $request)
    {
        $request->validate([
            'id_token'=>'required|string',
        ]);

        $idtoken=$request->id_token;
        $client=new Google_Client(['clientId'=>env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($idtoken);

        if($payload) {
            $user = User::where('email', $payload['email'])->first();
            $token = $user->createToken('auth_token')->plainTextTekon;
            if ($user) {
                return response()->json([
                    'status'=>'status',
                    'message'=>'User logged in successfully',
                    'data'=> [
                        'user'=>$user,
                        'token'=>$token,
                    ],
                ], 200);
            } else {
                $user = User::created([
                    'name'=>$payload['name'],
                    'email'=>$payload['email'],
                    'password'=>Hash::make($payload['sub']),
                ]);
                $user = User::where('email', $payload['email'])->first();

                return response()->json([
                    'status'=>'status',
                    'message'=>'User Created successfully',
                    'data'=> [
                        'user'=>$user,
                        'token'=>$token,
                    ],
                ], 201);
            }
        } else {
            return response()->json([
                'status'=>'error',
                'message'=>'Invalid id tOken',
            ], 400);
        }
    }

    public function login (Request $request)
    {
        $request ->validate([
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'=>'error',
                'message'=>'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'=>'success',
            'message'=>'User loged in successfully',
            'data'=>[
                'user'=>$user,
                'token'=>$token,
            ],
        ], 200);
    }

    public function logout (Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'User logged out successfully',
        ], 200);
    }
}
