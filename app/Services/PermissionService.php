<?php
namespace App\Services;

use App\Models\Permission;

class PermissionService
{
    public function register(array $inputs) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(function () use ($inputs) {
            $permission = Permission::create($inputs);
            return $permission;
        });
    }

    public function update(array $inputs, Permission $permission) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(function () use ($inputs, $permission) {
            $permission->update($inputs);
            return $permission;
        });
    }

    public function delete(Permission $permission) : ResultService
    {
        return app(TryCatchServiceWrapper::class)(fn () => $permission->delete());
    }
}
