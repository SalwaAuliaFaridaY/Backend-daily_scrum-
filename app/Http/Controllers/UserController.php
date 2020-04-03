<?php
namespace App\Http\Controllers;

use App\User; //tambahkan ini
use Illuminate\Http\Request;
//tambahkan ini
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    //tambahkan ini

    	//fungsi untuk login
	public function login(Request $request){
		$credentials = $request->only('email', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)){
				return response()->json([
						'logged' 	=>  false,
						'message' 	=> 'Invalid email and password'
					]);
			}
		} catch(JWTException $e){
			return response()->json([
						'logged' 	=> false,
						'message' 	=> 'Generate Token Failed'
					]);
		}
		return response()->json([
					"logged"    => true,
                    "token"     => $token,
                    "message" 	=> 'Login berhasil'
		]);
	}

    public function register(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'Firstname' => 'required|string|max:255',
        'Lastname' => 'required|string|max:255',
        'email' => 'required|string|email|max:100|unique:users',
        'password' => 'required_with:password_verify|same:password_verify|string|min:6|confirmed',
        'password_verify' => 'required|string|min:6',
        
      ]);
      if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
      }
      $user = User::create([
        'Firstname' => $request->get('Firstname'),
        'Lastname' => $request->get('Lastname'),
        'email' => $request->get('email'),
        'password' => Hash::make($request->get('password')),
        'password_verify' => Hash::make($request->get('password_verify')),
      ]);
      $token = JWTAuth::fromUser($user);
      return response()->json(compact('user','token'), 201);
    }

    public function LoginCheck(){
      try {
        if(!$user = JWTAuth::parseToken()->authenticate()){
          return response()->json([
              'auth' 		=> false,
              'message'	=> 'Invalid token'
            ]);
        }
      } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
        return response()->json([
              'auth' 		=> false,
              'message'	=> 'Token expired'
            ], $e->getStatusCode());
      } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
        return response()->json([
              'auth' 		=> false,
              'message'	=> 'Invalid token'
            ], $e->getStatusCode());
      } catch (Tymon\JWTAuth\Exceptions\JWTException $e){
        return response()->json([
              'auth' 		=> false,
              'message'	=> 'Token absent'
            ], $e->getStatusCode());
      }
  
       return response()->json([
           "auth"      => true,
                  "user"    => $user
       ], 201);
    }
  
    public function logout(Request $request)
      {
  
          if(JWTAuth::invalidate(JWTAuth::getToken())) {
              return response()->json([
                  "logged"    => false,
                  "message"   => 'Logout berhasil'
              ], 201);
          } else {
              return response()->json([
                  "logged"    => true,
                  "message"   => 'Logout gagal'
              ], 201);
          }
  
          
  
      }
}
