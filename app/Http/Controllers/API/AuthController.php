<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8'
       ]);

       if($validator->fails())
       {
          return response()->json([
               'validation_errors'=>$validator->messages()
          ]);
       }
       else
       {
       	  $user = User::create([
             'name'=>$request->name,
             'email'=>$request->email,
             'password'=>Hash::make($request->password)
       	  ]);
          
          //Generate Token
       	  $token = $user->createToken($user->email.'_Token')->plainTextToken;

       	  return response()->json([
               'status'=> 200,
               'username' => $user->name,
               'token' => $token,
               'message' => 'Registered Successfully!'
          ]);
       }
    }


    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required|min:8'
        ]);

        if($validator->fails())
        {
            return response()->json([
               'validation_errors'=>$validator->messages()
            ]);
        }
        else
        {  
           //Who is logged in
            $user = User::where('email', $request->email)->first();

		    if (! $user || ! Hash::check($request->password, $user->password)) {
		          
		          return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credential!'
                 ]); 
		    }
		    else
		    {     
		    	    
              if($user->role_as == 1) //1 = Admin
              {
                  $role = 'admin'; //this when u log in u will redirect to admin/dashbord
                  $token = $user->createToken($user->email.'_AdminToken', ['server:admin'])->plainTextToken; 
              }
              else
              {   
                  $role = ''; //this when u log in u will redirect to home or userf=dashboard it up to you.
                  //creating token and send that.
                  //Generate Token
                  $token = $user->createToken($user->email.'_Token', [''])->plainTextToken;
              }

		       	  return response()->json([
		               'status'=> 200,
		               'username' => $user->name,
		               'token' => $token,
                       'role' => $role,
		               'message' => 'Logged in Successfully!'
		          ]);      
		    }
        }
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
         return response()->json([
           'status'=> 200,
           'message' => 'Logged out Successfully!'
         ]);  
    }
}
