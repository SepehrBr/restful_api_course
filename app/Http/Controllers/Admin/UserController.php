<?php

namespace App\Http\Controllers\Admin;

use App\Http\ApiRequests\UserStoreApiRequest;
use App\Http\ApiRequests\UserUpdateApiRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserDetailsApiResource;
use App\Http\Resources\UsersListApiResource;
use App\Http\Resources\UsersListApiResourceCollection;
use App\Models\User;
use App\RestAPI\Facades\ApiResponse;
use App\RestAPI\َApiResponseBuilder;
use App\Services\UserService;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->paginate();

        return ApiResponse::withData(new UsersListApiResourceCollection($users))->build();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreApiRequest $request)
    {
        // send data to UserService to register new user
        $data = $this->userService->registerUser($request->validated());

        // return error message
        if (! $data->ok)
            return ApiResponse::withSuccess(false)->withMessage('An error occurred while creating the user.')->withAppends($data->data)->withStatus(500)->build();

        // retrun success message
        return ApiResponse::withMessage('User Created successfully!')->withData($data->data)->withStatus(201)->build();
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
    public function update(UserUpdateApiRequest $request, User $user)
    {
        // use UserService to update user
        $data = $this->userService->updateUser($request->validated(), $user);

        // return error messages if sth goes wrong as json
        if (! $data->ok)
            return ApiResponse::withSuccess(false)->withMessage('An error occurred while updating the user.')->withAppends( $data->data)->withStatus(500)->build();

        // return success responses as json
        return ApiResponse::withMessage('User Updated successfully!')->withData($data->data)->withStatus(201)->build();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // delete user using UserService
        $data = $this->userService->deleteUser($user);

        // return error message as JSON if sth goes wrong
        if (! $data->ok)
            return ApiResponse::withSuccess(false)->withMessage('An error occurred while deleting the user.')->withAppends( $data->data)->withStatus(500)->build();

        // return success message as JSON
        return ApiResponse::withMessage('User Deleted successfully!')->withData($data->data)->withStatus(201)->build();
    }
}
