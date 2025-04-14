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

        return $this->apiResponse(message: null, data: UsersListApiResource::collection($users));
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

            return $this->apiResponse(message: 'User Created successfully!', data: $user, status: 201);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return $this->apiResponse(false,'An error occurred while creating the user.', appends: [ 'error' => $th->getMessage() ], status: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->apiResponse(message: null, data: new UserDetailsApiResource($user));
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

            return $this->apiResponse(message: 'User Updated successfully!', data:$user, status: 201);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return $this->apiResponse(false,'An error occurred while updating the user.', appends: [ 'error' => $th->getMessage() ], status: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return $this->apiResponse(message: 'User Deleted successfully!', data: $user, status: 201);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return $this->apiResponse(false,'An error occurred while deleting the user.', appends: [ 'error' => $th->getMessage() ], status: 500);
        }
    }

    private function apiResponse(bool $success = true, ?string $message = null, mixed $data = null, array $appends = [], int $status = 200)
    {
        $body = [];

        ! is_null($success) && $body['success'] = $success;
        ! is_null($message) && $body['message'] = $message;
        ! is_null($data) && $body['data'] = $data;
        $body += $appends;

        return response()->json($body, $status);
    }
}
