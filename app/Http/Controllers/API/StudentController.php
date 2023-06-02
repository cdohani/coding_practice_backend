<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPermissions;
use App\Models\Student;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:students,email',
            'password' => 'required|max:255',
            'block' => 'required|max:255',
            'branch' => 'required|max:255',
            'registrationNo' => 'required|max:255',
            'phoneNumber' => 'required|max:255',
            'roomNo' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Student::create($input);
        $user->assignRole("student");
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if ($this->studentGuard()->attempt(['id' => $request->id, 'password' => $request->password])) {
            $user = $this->studentGuard()->user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $success['permissions'] = UserPermissions::collection($user->getAllPermissions());
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 422);
        }
    }


    protected function studentGuard()
    {
        return Auth::guard('students');
    }
    public function logout(Request $request)
    {

        // Get the authenticated user
        $user = Auth::user();

        if ($user) {
            // Perform any additional logic you need for logout
            // For example, you can invalidate any tokens or session data

            // Logout the user
            $request->user()->currentAccessToken()->delete();

            // Return a response indicating successful logout
            return $this->sendResponse('', 'User logout successfully.');
        }

        // Return a response indicating that the user is not authenticated
        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
    }
}
