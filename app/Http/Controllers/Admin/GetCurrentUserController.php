<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\RestAPI\Facades\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetCurrentUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return ApiResponse::withAppends([
            'tokens' => $request->user()->tokens,
            'current_token' => $request->user()->currentAccessToken()
        ])->build();
    }
}
