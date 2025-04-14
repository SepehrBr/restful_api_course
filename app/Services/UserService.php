<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class UserService
{
    /**
     * handles creating new user
     * @param array $inputs
     * @return ResultService
     */
    public function registerUser(array $inputs) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(function () use ($inputs) {
            $inputs['password'] = bcrypt($inputs['password']);
            return User::create($inputs);
        });
    }

    /**
     * user service which updates user info
     * @param array $inputs
     * @param \App\Models\User $user
     * @return ResultService
     */
    public function updateUser(array $inputs, User $user) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(function () use ($inputs, $user) {
            if (request()->has('password')) {
                $inputs['password'] = bcrypt($inputs['password']);
            }
            $user->update($inputs);
            return $user;
        });
    }

    /**
     * method for deleting user
     * @param \App\Models\User $user
     * @return ResultService
     */
    public function deleteUser(User $user) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(fn () => $user->delete);
    }
}
