<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    /**
     * login function
     */
    public function login(Request $request) 
    {
        try{
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = User::where('email', $request->email)->first();
            if (! Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in login!');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,      
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

    /**
     * resgiter function
     * after user register, they dont have any token. they must login to get a token
     */
    public function register(Request $request) 
    {
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'User successfully created',
                    'data' => $user
                ]);
            }
        } catch(Exception $error) {
            return response()->json([
                'message' => 'Error in Register',
                'error' => $error,
            ]);
        }
    }


}
