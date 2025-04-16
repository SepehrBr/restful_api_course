<?php

namespace App\Http\ApiRequests\Permission;

use App\Models\Permission;
use App\RestAPI\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionApirRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return Permission::rules([
            'name' => ['nullable','string','max:255', Rule::unique('permissions', 'name')->ignore($this->permission->id)],
            'display_name' => 'nullable|string|max:255',
        ]);
    }
}
