<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ApiAuthController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'personal_code' => 'required|unique:users',
            'email'         => 'required|email|unique:users',
            'password'      => 'required',
            'c_password'    => 'required|same:password',
        ]);


        if($validator->fails()){

        	$response = [
	            'success' => false,
	            'message' => 'Validation Error.'
	        ];
	        if(!empty($validator->errors())){
	            $response['data'] = $validator->errors();
	        }

	        return response()->json($response, 404);
        }

 
        $user = User::create([
            'name'          => $request->name,
            'personal_code' => $request->personal_code,
            'email'         => $request->email,
            'password'      => bcrypt($request->password)
        ]);
 
        $token = $user->createToken('ProjectMBS')->accessToken;
 
        $response = [
            'success' => true,
            'data'    => array(
            	'name' 	=> $user->name,
            	'token'	=> $token
            ),
            'message' => 'User register successfully.'
        ];

        return response()->json($response, 200);
    }
 
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password
        ];
 
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('ProjectMBS')->accessToken;
            return response()->json(['success' => true, 'token' => $token, 'message' => 'Login successfully.'], 200);
        } else {
            return response()->json(['success' => false, 'error' => 'UnAuthorised'], 401);
        }
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['success' => true, 'user' => auth()->user()], 200);
    }
}
