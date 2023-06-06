<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPermissions;
use App\Models\Student;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class StudentController extends BaseController
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:students,email',
            'password' => 'required|max:255',
            // 'block' => 'required|max:255',
            // 'branch' => 'required|max:255',
            // 'registrationNo' => 'required|max:255',
            // 'phoneNumber' => 'required|max:255',
            // 'roomNo' => 'required|max:255',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['name'] = "test";
        $input['block'] = "test";
        $input['branch'] = "test";
        $input['registrationNo'] = "test";
        $input['phoneNumber'] = "test";
        $input['course'] = "test";
        $input['roomNo'] = "test";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imagePath = $image->storeAs('public/images', $imageName);
            $input["image"] = $imagePath;
        }

        $user = Student::create($input);
        $user->assignRole("student");
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User registered successfully.');
    }

    public function login(Request $request)
    {

        if ($this->studentGuard()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = $this->studentGuard()->user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            // $success['image'] = url(Storage::url($user->image));
            $success['permissions'] = UserPermissions::collection($user->getAllPermissions());
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 422);
        }
    }

    public function getStudentPermissions()
    {
        $user = Auth::user();
        if ($user) {
            $success['name'] = $user->name;
            $success['permissions'] = UserPermissions::collection($user->getAllPermissions());
            return $this->sendResponse($success, 'Permissions.');
        }
        else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 422);
        }
    }


    protected function studentGuard()
    {
        return Auth::guard('students');
    }
    public function logout(Request $request)
    {
        if (Auth::user()) {
            $request->user()->currentAccessToken()->delete();
            return $this->sendResponse('', 'User logout successfully.');
        }
        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
    }

    protected function authenticationUsingImage($user, $request)
    {

        //$client = new Client();
        $request_image = base64_encode(file_get_contents($request->image));
        $user_image = base64_encode(file_get_contents(url(Storage::url($user->image))));
        return $user_image;
        // try {
        //     $response = $client->post('https://faceapi.mxface.ai/api/v3/face/verify', [
        //         'headers' => [
        //             'Content-Type' => 'application/json',
        //             'Subscriptionkey' => 'Eab8vbwNnRKz0MJHsb-3yWZmDP5Hv1595',
        //         ],
        //         'json' => [
        //             'encoded_image1' => Storage::url($requestImage),
        //             'encoded_image2' => Storage::url($userImageDataimage)
        //         ],
        //     ]);

        //     $statusCode = $response->getStatusCode();
        //     $body = $response->getBody();
        //     return $body;
        //     // Process the response data as needed
        //     // ...
        // } catch (\Exception $e) {
        //     return $e;
        //     // Handle request exceptions
        //     // ...
        // }
    }
}
