<?php

namespace App\Http\Controllers\Admin;

use App\Http\ApiRequests\Permission\AllPermissionsApirRequest;
use App\Http\ApiRequests\Permission\SinglePermissionApirRequest;
use App\Http\ApiRequests\Permission\StorePermissionApirRequest;
use App\Http\ApiRequests\Permission\UpdatePermissionApirRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Permission\PermissionDetailsApiResource;
use App\Http\Resources\Permission\PermissionsListApiResourceCollection;
use App\Models\Permission;
use App\RestAPI\Facades\ApiResponse;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(private PermissionService $permissionService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(AllPermissionsApirRequest $request)
    {
        $permissions = Permission::paginate();
        return ApiResponse::withData(new PermissionsListApiResourceCollection($permissions))->build();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionApirRequest $request)
    {
        $response = $this->permissionService->register($request->validated());

        if (! $response->ok)
            return ApiResponse::withSuccess(false)
                ->withMessage('Something Goes Wrong. Please Try Again Later.')
                ->withAppends([ 'error' => $response->data ])
                ->withStatus(500)
                ->build();

        return ApiResponse::withMessage('Permission Created Successfully!')
            ->withData($response->data)
            ->withStatus(201)
            ->build();
    }

    /**
     * Display the specified resource.
     */
    public function show(SinglePermissionApirRequest $request, Permission $permission)
    {
        return ApiResponse::withData(new PermissionDetailsApiResource($permission))->build();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionApirRequest $request, Permission $permission)
    {
        $response = $this->permissionService->update($request->validated(), $permission);

        if (! $response->ok)
            return ApiResponse::withSuccess(false)
                ->withMessage('Something Goes Wrong. Please Try Again Later.')
                ->withAppends([ 'error' => $response->data ])
                ->withStatus(500)
                ->build();

        return ApiResponse::withMessage('Permission Updated Successfully!')
            ->withData($response->data)
            ->withStatus(201)
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $response = $this->permissionService->delete($permission);

        if (! $response->ok)
            return ApiResponse::withSuccess(false)
                ->withMessage('Something Goes Wrong. Please Try Again Later.')
                ->withAppends([ 'error' => $response->data ])
                ->withStatus(500)
                ->build();

        return ApiResponse::withMessage('Permission Deleted Successfully!')
            ->withData($response->data)
            ->withStatus(201)
            ->build();
    }
}
