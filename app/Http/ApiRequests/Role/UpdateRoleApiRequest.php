<?php

namespace App\Http\ApiRequests\Role;

use App\Models\Role;
use App\RestAPI\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleApiRequest extends ApiFormRequest
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
        return Role::rules([
            'name' => ['nullable','string','max:255', Rule::unique('roles', 'name')->ignore($this->role->id)],
            'display_name' => 'nullable|string|max:255',
        ]);
    }
}
