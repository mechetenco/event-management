<?php
 
 namespace App\Http\Controllers\Api;
 
 use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
 use Illuminate\Support\Facades\Hash;
 use Illuminate\Validation\ValidationException;
 use Illuminate\Support\Facades\Password;
use App\Notifications\ApiResetPasswordNotification;

 
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




     public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $status = Password::sendResetLink($request->only('email'));

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
            'message' => __('Password reset link sent successfully.'),
            'status' => $status,
        ], 200);
    }

    return response()->json([
        'error' => __('Failed to send password reset link.'),
        'status' => $status,
    ], 422);
}

  public function showResetForm(Request $request, $token)
{
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->email
    ]);
}
public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
    ? response()->json(['message' => 'Password reset successful'], 200)
    : response()->json(['error' => __($status)], 400);

}


   

 
     public function logout(Request $request)
     {
        $request->user()->tokens()->delete();
 
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
     }
 }