<?php
namespace App\Services;

use App\Models\Role;

class RoleService
{
    public function register(array $inputs) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(function () use ($inputs) {
            $role = Role::create($inputs);
            return $role;
        });
    }

    public function update(array $inputs, Role $role) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(function () use ($inputs, $role) {
            $role->update($inputs);
            return $role;
        });
    }

    public function delete(Role $role) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(fn () => $role->delete());
    }
}
