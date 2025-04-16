<?php

namespace App\Http\Controllers\Admin;

use App\Http\ApiRequests\Role\AllRolesApiRequest;
use App\Http\ApiRequests\Role\SingleRoleApiRequest;
use App\Http\ApiRequests\Role\StoreRoleApiRequest;
use App\Http\ApiRequests\Role\UpdateRoleApiRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleDetailsApiRequest;
use App\Http\Resources\Role\RolesListApiRequestCollection;
use App\Models\Role;
use App\RestAPI\Facades\ApiResponse;
use App\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(AllRolesApiRequest $request)
    {
        $roles = Role::paginate();
        return ApiResponse::withData(new RolesListApiRequestCollection($roles))->build();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleApiRequest $request)
    {
        $response = $this->roleService->register($request->validated());

        if (! $response->ok)
            return ApiResponse::withSuccess(false)
                ->withMessage('Something Goes Wrong. Please Try Again Later.')
                ->withAppends([ 'error' => $response->data ])
                ->withStatus(500)
                ->build();

        return ApiResponse::withMessage('Role Created Successfully!')
            ->withData($response->data)
            ->withStatus(201)
            ->build();
    }

    /**
     * Display the specified resource.
     */
    public function show(SingleRoleApiRequest $request, Role $role)
    {
        return ApiResponse::withData(new RoleDetailsApiRequest($role))->build();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleApiRequest $request, Role $role)
    {
        $response = $this->roleService->update($request->validated(), $role);

        if (! $response->ok)
            return ApiResponse::withSuccess(false)
                ->withMessage('Something Goes Wrong. Please Try Again Later.')
                ->withAppends([ 'error' => $response->data ])
                ->withStatus(500)
                ->build();

        return ApiResponse::withMessage('Role Updated Successfully!')
            ->withData($response->data)
            ->withStatus(201)
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $response = $this->roleService->delete($role);

        if (! $response->ok)
            return ApiResponse::withSuccess(false)
                ->withMessage('Something Goes Wrong. Please Try Again Later.')
                ->withAppends([ 'error' => $response->data ])
                ->withStatus(500)
                ->build();

        return ApiResponse::withMessage('Role Deleted Successfully!')
            ->withData($response->data)
            ->withStatus(201)
            ->build();
    }
}
