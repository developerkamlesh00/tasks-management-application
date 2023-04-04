<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterOrganization extends Controller
{
    
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'org_name' => 'required',
            'org_email' => 'required|email',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 202);
        }

        try {

            $input = $request->only('org_name', 'org_email');
            $organization = Organization::create($input);

            $input = $request->only('name', 'email', 'password');
            $input['password'] = bcrypt($input['password']);
            $input['organization_id'] = $organization->id;
            $input['role_id'] = 2;

            $user = User::create($input);

            $resposeArray = [];
            $resposeArray['token'] = $user->createToken('DirectorToken')->accessToken;
            $resposeArray['name'] = $user->name;
            $resposeArray['role'] = $user->role->role_name;
            $resposeArray['userId'] = $user->id;
            
            //return response()->json($organization);
            return response()->json($resposeArray, 200);
        } catch (Exception $e) {
            return response()->json($e, 202);
        }
    }
}
