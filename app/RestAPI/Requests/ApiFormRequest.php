<?php
namespace App\RestAPI\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response:
                response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ],422)
        );
    }
}
