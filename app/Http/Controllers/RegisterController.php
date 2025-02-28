<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        request()->validate( [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'profile' => 'nullable|exists:profiles,name'
        ]);

        $profileName = config('users.default_user_profile');
        if($request->has('profile')){
            if (request()->user()->profile->name !== 'ADMIN') {
                throw new Exception('Unauthorized', 403);
            }
            $profileName = $request->profile;
        }
        $input = $request->all();

        $input['profile_id'] = Profile::where('name', strtoupper($profileName))->firstOrFail()->id;
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        // return $this->sendResponse($success, 'User register successfully.');
        return response()->json($success, 201);
    }

    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Unauthorised'], 401);
        }


        $user = Auth::user();
        // dd($user->tokens()->);
        $success['token'] =  $user->createToken('MyApp', ['*'], Carbon::now()->addHours(config('sanctum.expiration')))->plainTextToken;
        $success['name'] =  $user->name;

        // return $this->sendResponse($success, 'User login successfully.');
        return response()->json($success, 200);
    }
}
