<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserDetailsApiResource;
use App\Http\Resources\UsersListApiResource;
use App\Models\User;
use App\RestAPI\Facades\ApiResponse;
use App\RestAPI\َApiResponseBuilder;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->paginate();

        return ApiResponse::withData(UsersListApiResource::collection($users))->build();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);

            return ApiResponse::withMessage('User Created successfully!')->withData($user)->withStatus(201)->build();
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return ApiResponse::withSuccess(false)->withMessage('An error occurred while creating the user.')->withAppends( [ 'error' => $th->getMessage() ])->withStatus(500)->build();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return ApiResponse::withData(new UserDetailsApiResource($user))->build();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['sometimes', 'required', 'string', 'max:255'],
                'last_name' => ['sometimes', 'required', 'string', 'max:255'],
                'email' => [ 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
                'password' => ['nullable', 'string', 'min:5', 'confirmed'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'data' => $request->all(),
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            if ($request->has('password')) {
                $data['password'] = bcrypt($data['password']);
            }

            $user->update($data);

            return ApiResponse::withMessage('User Updated successfully!')->withData($user)->withStatus(201)->build();
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return ApiResponse::withSuccess(false)->withMessage('An error occurred while updating the user.')->withAppends( [ 'error' => $th->getMessage() ])->withStatus(500)->build();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return ApiResponse::withMessage('User Deleted successfully!')->withData($user)->withStatus(201)->build();
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return ApiResponse::withSuccess(false)->withMessage('An error occurred while deleting the user.')->withAppends( [ 'error' => $th->getMessage() ])->withStatus(500)->build();
        }
    }
}
