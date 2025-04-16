<?php

namespace App\Http\Resources\Role;

use App\Http\Resources\Permission\PermissionsListApiResource;
use App\Http\Resources\User\UsersListApiResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleDetailsApiRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'user_id' => UsersListApiResource::collection($this->users),
            'permissions' => PermissionsListApiResource::collection($this->permissions),
            'created_at' => (new Carbon($this->created_at))->format('Y-m-d '),
            'updated_at' => (new Carbon($this->updated_at))->format('Y-m-d '),
        ];
    }
}
