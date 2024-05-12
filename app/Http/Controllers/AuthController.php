<?php

namespace App\Http\Controllers;

use Auth;
use App\models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUserRequest;

class AuthController extends Controller
{
    //
    public function Register(RegisterUserRequest $request)
    {
        $request->validated();
        try{
             DB::beginTransaction();
              $user=User::create([
                'name'=>$request->name,
                'email' => $request->email,
                 'password'=>Hash::make($request->password),

              ]);
              $token=Auth::login($user);
              $role = Auth::user();
              DB::commit();
              return response()->json([
                'status'=>'تم اضافة حساب',
                'user'=>$user,
                'token'=>$token,
                'role'=>$role->role,
              ]);
        }
        catch(Throwable $th)
        {
            Log::error($th->getMessage());
            Log::Debug($th);
            return response()->json([
                'status'=>'error'
            ]);
        }

    }
    public function login(LoginUserRequest $request)
    {

        $request->validated;
        try{

            $request->validated();
            $credentials = $request->only('email','password');
            $token = Auth::attempt($credentials);
              if(!$token){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
              };
              $user = Auth::user();
              return response()->json([
                'status'=>' تم تسجيل الدخول  ',
                'user' => $user,
                'role'=>$user->role,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',

]              ]);
         }
         catch(\Throwable $th){
            Log::debug($th);
            $e=\Log::error( $th->getMessage());
            return response()->json([
                'status' =>'error',

              ]);
         }



    }
    public function logout(){
        Auth::logout();
        return response()->json([
           'status'=>'تم تسجيل الخروج'
        ]);
    }
    //get profile user is Auth
    public function index()
    {
        $user=Auth::user();
            return response()->json([
                'stauts'=>'profile',
                'user'=>$user
            ]);
    }
    //update user
       public function Update(Request $request,$id)
       {
        try{
            DB::beginTransaction();
            $user = User::where('id',$id)->first();

            if($user->id == Auth::user()->id){

      $newData=[];
      if(isset($request->name)){
        $newData['name']=$request->name;
      }
      if(isset($request->email)){
        $newData['email']=$request->email;
      }
      if(isset($request->password)){
        $newData['password']=$request->password;
      }
      $user->update($newData);
 DB::commit();
 return response()->json([
    'status'=>'update',
    'user'=>$user
 ]);
        }
        return response()->json([
            'status'=>'not allowed is profile to user anathor',
         ]);
    }
        catch(Throwabe $th){
            Log::debug($th);
            $e=\Log::error( $th->getMessage());
            return response()->json([
                'status' =>'error',

              ]);
        }
       }
}
