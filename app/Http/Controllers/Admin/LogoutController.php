<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\RestAPI\Facades\ApiResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // delete all tokens
        // $request->user()->tokens()->delete();

        // delete from current token
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::withMessage('Logged out successfully.')->build();
    }
}
