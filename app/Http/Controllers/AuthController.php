<?php

namespace App\Http\Controllers;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //
    public function __construct(){
        $this->middleware('jwt.auth',['except' => ['login','register']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required','email'],
            'password' => ['required','string','min:6','max:10']
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $credentials = $request->only('email','password');
        $this->guard()->factory()->setTTL( 24 * 60);
        if(! $token = $this->guard()->attempt($validator->validated())){
            return response()->json([
                'type' => 'error',
                'message' => 'unauthorized'
            ]);
        }

        return response()->json([
            'type' => 'success',
            'token' => $token
        ]);
    }

    public function register(Request $request){
        $this->validate($request, [
            'name' => ['required','string'],
            'email' => ['required','email','unique:users'],
            'password' => ['required','string','max:12','min:6']
        ]);
        
        $details = ['name','email','password'];
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $save = $user->save();
        if($save){
            /*return response()->json([
                'status' => 'success',
                'message' => 'saved user'
            ]);*/
            return $this->login($request);
        }
    }
    public function logout(){
        $this->guard()->logout();
        return response()->json([
            'type' => 'success',
            'message' => 'user logged out successfully'
        ]);
    }
    public function user(){
        return response()->json($this->guard()->user());
    }
    public function refresh(){
        return response()->json($this->guard()->refresh());
    }

    protected function guard(){
        return Auth::guard();
    }

}
