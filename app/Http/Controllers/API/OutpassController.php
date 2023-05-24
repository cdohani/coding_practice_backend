<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OutpassResource;
use App\Models\Outpass;
use App\Models\User;
use App\Notifications\OutpassStatusChanged;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OutpassController extends BaseController
{

    public function index()
    {
        return $this->sendResponse(
            [
                "outpassess" => OutpassResource::collection(Outpass::getOutpassessWithStudents())
            ]
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outpass_date' => 'required|max:255',
            'outpass_from' => 'required|max:255',
            'outpass_to' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['student_id'] = Auth::user()->id;
        $input["status"]="Pending";
        $outpass = Outpass::create($input);
        return $this->sendResponse('', 'Outpass Application successfully.');
    }

    public function outpassStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:outpasses,id',
            'status' => 'required|in:approved,rejected',
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $outpass=Outpass::find($request->id);
        $outpass->update(["status"=>$request->status]);

        User::find(Auth::user()->id)->notify(new OutpassStatusChanged($request->status));
        return $this->sendResponse('', 'Outpass Application successfully.');
    }
}
