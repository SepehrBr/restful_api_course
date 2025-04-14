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

        return response()->json([
            'users' => UsersListApiResource::collection($users)
        ]);
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

            return response()->json([
                'success' => true,
                'message' => 'User Created successfully!',
                'data' => $user
            ], 201);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => new UserDetailsApiResource($user)
        ]);
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

            return response()->json([
                'success' => true,
                'message' => 'User Updated successfully!',
                'data' => $user
            ], 201);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User Deleted successfully!',
                'data' => $user
            ], 201);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the user.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
