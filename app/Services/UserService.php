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
        try {
            $inputs['password'] = bcrypt($inputs['password']);
            $user = User::create($inputs);

            return new ResultService(true, $user);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return new ResultService(false, $th->getMessage());
        }
    }

    /**
     * user service which updates user info
     * @param array $inputs
     * @param \App\Models\User $user
     * @return ResultService
     */
    public function updateUser(array $inputs, User $user) : ResultService
    {
        try {
            if (request()->has('password')) {
                $inputs['password'] = bcrypt($inputs['password']);
            }
            $user->update($inputs);

            return new ResultService(true, $user);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return new ResultService(false, $th->getMessage());
        }
    }

    /**
     * method for deleting user
     * @param \App\Models\User $user
     * @return ResultService
     */
    public function deleteUser(User $user) : ResultService
    {
        try {
            $user->delete();

            return new ResultService(true, $user);
        } catch (Throwable $th) {
            app()[ExceptionHandler::class]->report($th);

            return new ResultService(false, $th->getMessage());
        }
    }
}
