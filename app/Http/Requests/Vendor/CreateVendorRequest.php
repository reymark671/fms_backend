<?php

namespace App\Http\Requests\Vendor;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;

class CreateVendorRequest extends FormRequest
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
        return [
            'first_name' =>'required|max:255',
            'last_name' =>'required|max:255',
            'email' => 'required|email|max:255|unique:vendors,email',
            'company_name' =>'required|max:255',
            'mobile' =>'required|max:255',
            'phone' =>'required|max:255',
            'address_1' =>'required|max:255',
            'address_2' =>'max:255',
            'tin' => 'required|digits:9',
            'city' =>'required|max:255',
            'state' =>'required|max:255',
            'zipcode' =>'required|max:255',
            'password' =>'required|max:255',
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
