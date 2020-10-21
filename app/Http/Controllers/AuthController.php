<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     * 
     * @OA\Post(path="/api/auth/signup",
     *   tags={"Auth"},
     *   summary="Sign up a user",
     *   description="",
     *   operationId="signup",
     *   @OA\Response(
     *     response=200,
     *     description="The new notification",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Server error"
     *   ),
     *   @OA\Response(
     *     response=401, 
     *     description="UnAuthorize request"
     *   )
     * )
     */        
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);        
        $user->save();        
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     * 
     * @OA\Post(path="/api/auth/login",
     *   tags={"Auth"},
     *   summary="Login a user",
     *   description="",
     *   operationId="login",
     *   @OA\Response(
     *     response=200,
     *     description="The new notification",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Server error"
     *   ),
     *   @OA\Response(
     *     response=401, 
     *     description="UnAuthorize request"
     *   )
     * )
     */        
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]); 

        $credentials = request(['email', 'password']);        
        
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);        

        $user = $request->user();        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;        

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);        

        $token->save();        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     * 
     * @OA\Get(path="/api/auth/logout",
     *   tags={"Auth"},
     *   summary="Logout a user",
     *   description="",
     *   operationId="logout",
     *   @OA\Response(
     *     response=200,
     *     description="Operation success",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Server error"
     *   ),
     *   @OA\Response(
     *     response=401, 
     *     description="UnAuthorize request"
     *   )
     * )
     */        
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object

     * @OA\Get(path="/api/auth/user",
     *   tags={"Auth"},
     *   summary="Get user´s information",
     *   description="",
     *   operationId="user",
     *   @OA\Response(
     *     response=200,
     *     description="Operation success",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Server error"
     *   ),
     *   @OA\Response(
     *     response=401, 
     *     description="UnAuthorize request"
     *   )
     * )
     */        
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}