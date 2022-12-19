<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $cred = ['email' => $request->email, 'password' => $request->password];
        if (auth()->attempt($cred)) {
            $user = auth()->user();
            return response()->json(['data' => ['token' => $user->createToken('api-token')->plainTextToken, 'user' => $user], 'status' => true, 'message' => 'logged in '],200);

        } else {
            return response()->json(['data' => [], 'status' => false, 'message' => 'please check username and password'],422);
        }
    }
        public
        function logout(Request $request)
        {
            $user = auth()->user();


            if ($user->tokens()->delete()) {
                return response()->json(['data' => [], 'status' => true, 'message' => 'logged out  '],200);

            } else {
                return response()->json(['data' => [], 'status' => false, 'message' => 'something went wrong!'],422);
            }
        }

}
