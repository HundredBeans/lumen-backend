<?php

namespace App\Http\Controllers;

use App\AdminToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance for Authentication (login and register).
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    // Register new User
    public function register(Request $request)
    {
        $name = $request->input('name');
        $username = $request->input('username');
        $password = Hash::make($request->input('password'));

        $register = User::create([
            'name' => $name,
            'username' => $username,
            'password' => $password,
        ]);

        if ($register) {
            return response() ->json([
                'success' => true, 
                'message' => 'Register Success',
                'data' => $register
            ], 201);
        }else{
            return response() ->json([
                'success' => false, 
                'message' => 'Register Fail',
                'data' => ''
            ], 400);
        }
    }
    // Login User
    public function login(Request $request)
    {
        $username = $request -> input('username');
        $password = $request -> input('password');

        // Admin Login
        if ($username == 'admin' && $password == 'admin123') {
            $newToken = $this -> generateToken();

            $adminToken = AdminToken::all();
            if (count($adminToken) < 1){
                $newAdminToken = new AdminToken();
                $newAdminToken->token = $newToken;
                $newAdminToken->save();

                return response()->json([
                    'success'=>true,
                    'message'=>'Logged in as admin success',
                    'token'=>$newToken
                ], 200);
            }else{
                $adminToken = $adminToken->first();
                $adminToken->token = $newToken;
                $adminToken->save();

                return response()->json([
                    'success'=>true,
                    'message'=>'Logged in as admin success',
                    'token'=>$newToken
                ], 200);
            }
        }
        $user = User::where('username', $username)->first();

        if (Hash::check($password, $user->password)){
            $createToken = $this -> generateToken();

            $user->token = $createToken;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Login Success',
                'token' => $createToken,
                'data' => [
                    'user' => $user,
                ]
                ], 200);
        }else{
            return response()->json([
                'success' => false, 
                'message' => 'Login Fail',
                'data' => ''
            ]);
        }
    }

    public function generateToken($length=80)
    {
        $character = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $character[rand(0, strlen($character) - 1)];
        }
        return $str;        
    }
    //
}
