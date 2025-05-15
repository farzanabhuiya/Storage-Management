<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

  // login
  public function login(Request $request)
  {
    $request->validate([
      'email'    => 'email|required',
      'password' => 'required'
    ]);
    if (Auth::attempt(['email' =>  $request->email, 'password' =>  $request->password])) {

      $user = User::where('email', $request->email)->first();
      $tokon = $user->createToken('apiTokon' . $user->name)->plainTextToken;

      return response()->json([
        'status' => true,
        'message' => "Login successful",
        'tokon' => $tokon,

      ]);
    }
  }

  //////// register


  // FOR USER REGISTER API 

     public $rules;
     public $messages;

//ASSIN A RULES IN RULES VARIABLE

public function __construct()
{
   
    // VALIDATE ROULES
    $this->rules = [
        // 'roles_id' => 'required|exists:roles,id',
        'name' => 'required|max:100', 
        'email' => 'required|email|unique:users,email',
        'password' => [
            'required',
            'min:8'
              
        ]
    ];

    //CUSTOME RULES
    $this->messages = [
        'email.required' => 'The email field is required!',
        'email.unique' => 'Email already exists!',
        'password.required' => 'You must provide a password.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.max' => 'Password must be at most 20 characters.',
        'password.confirmed' => 'Passwords do not match.',
        'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
    ];
}


public function validateData($data)
{
    return Validator::make($data, $this->rules, $this->messages);
}

// USER REGISTER FUNCTION START
    function register(Request  $request)
    {

        
        $validator = $this->validateData($request->all());

       
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }
        // return $request->all();
        // return response()->json([
        //     'data'=>$request->all(),
        // ]);

        $user = User::create([
            "name" => $request->name,
           

            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $Token = $user->createToken('apitokan' . $user->name)->plainTextToken;
        // $email = $request->email;

        // $expires = now()->addMinutes(30);
        // Mail::to($email)->send(new EmailVerification($email, $expires));
        return [
            'user' => $user,
            "token" => $Token,

        ];
    }



                      // forgot-password
public function sendResetCode(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $code = rand(100000, 999999);
    $user = User::where('email', $request->email)->first();
    $user->reset_code = $code;
    $user->reset_code_expires_at = now()->addMinutes(10);
    $user->save();

    return response()->json(['message' => 'Verification code sent to your email.']);
}
   


              //  verif
public function verifyResetCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|digits:6',
    ]);

    $user = User::where('email', $request->email)
                ->where('reset_code', $request->code)
                ->where('reset_code_expires_at', '>', now())
                ->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid or expired code.'], 422);
    }

    return response()->json(['message' => 'Code verified.']);
}


                    //  resetPassword
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|digits:6',
        'password' => 'required|confirmed|min:6',
    ]);

    $user = User::where('email', $request->email)
                ->where('reset_code', $request->code)
                ->where('reset_code_expires_at', '>', now())
                ->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid or expired code.'], 422);
    }

    $user->password = Hash::make($request->password);
    $user->reset_code = null;
    $user->reset_code_expires_at = null;
    $user->save();

    return response()->json(['message' => 'Password has been reset successfully.']);
}




  //  logout
  public function logOut(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'status' => true,
      'message' => "Logout successful"
    ]);
  }

}
