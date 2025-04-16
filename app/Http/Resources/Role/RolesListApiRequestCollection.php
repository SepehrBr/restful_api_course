<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RolesListApiRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'roles' => RolesListApiRequest::collection($this->collection),
            'meta' => [
                'total_per_page' => $this->collection->count(), // or you can implement $this->perPage()
                'total_data' => $this->total(),
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
            'links' => [
                'first_page' => $this->url(1),
                'last_page' => $this->url($this->lastPage()),
                'next_page' => $this->nextPageUrl(),
                'previous_page' => $this->previousPageUrl(),
            ],
        ];
    }
}
