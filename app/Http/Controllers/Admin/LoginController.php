<?php

namespace App\Http\Controllers\Admin;

use App\Http\ApiRequests\Auth\LoginApiRequest;
use App\Http\Controllers\Controller;
use App\RestAPI\Facades\ApiResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginApiRequest $request)
    {
        // return error if user's credentials doesnt match
        if (! Auth::attempt($request->validated()))
            return ApiResponse::withSuccess(false)->withMessage(__('auth.failed'))->withStatus(401)->build();

        // login user and generate token
        $user = Auth::user();
        $token = $request->user()->createToken('API TOKEN')->plainTextToken;

        // send authed user's fullname and token as json
        return ApiResponse::withMessage("Welcome $user->full_name")->withData($user)->withAppends([ 'token' => $token ])->build();
    }
}
