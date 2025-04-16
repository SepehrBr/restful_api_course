<?php

namespace App\Http\Resources\Permission;

use App\Http\Resources\Role\RolesListApiRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionDetailsApiResource extends JsonResource
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
            'roles' => RolesListApiRequest::collection($this->roles),
            'created_at' => (new Carbon($this->created_at))->format('Y-m-d '),
            'updated_at' => (new Carbon($this->updated_at))->format('Y-m-d '),
        ];
    }
}
