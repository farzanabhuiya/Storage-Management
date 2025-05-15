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
            // 'max:20',
            // 'confirmed',
            // 'regex:/[A-Z]/',  
            // 'regex:/[a-z]/',  
            // 'regex:/[0-9]/',  
            // 'regex:/[\W]/'    
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

  //  logout
  public function logOut(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'status' => true,
      'message' => "Logout successful"
    ]);
  }


  // getuserinfo
  // public function getuserinfo(Request $request)
  // {
  //   $user = auth()->user();
  //   return response()->json([
  //     'status' => true,
  //     'user' => $user,
  //   ]);
  // }
}
