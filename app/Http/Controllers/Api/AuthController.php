<?php
 
 namespace App\Http\Controllers\Api;
 
 use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Validation\ValidationException;
 
 class AuthController extends Controller
 {

 public function register(Request $request)
    {
       $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    
    }
    
     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required|email',
             'password' => 'required'
         ]);
 
         $user = \App\Models\User::where('email', $request->email)->first();
 
         if (!$user) {
             throw ValidationException::withMessages([
                 'email' => ['The provided credentials are incorrect.']
             ]);
         }
 
         if (!Hash::check($request->password, $user->password)) {
             throw ValidationException::withMessages([
                 'email' => ['The provided credentials are incorrect.']
             ]);
         }
 
         $token = $user->createToken('api-token')->plainTextToken;
 
         return response()->json([
             'token' => $token
         ]);
     }
 
     public function logout(Request $request)
     {
        $request->user()->tokens()->delete();
 
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
     }
 }