<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
class CreateReportRequest extends FormRequest
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
            'report_type' =>'required|max:255',
            'description' =>'required|max:255',
            'report_date' =>'required|date',
            'uploaded_by' => 'max:255',
            'report_destination' => 'required|in:1,2,3',
            'destination_account' => 'required|array|min:1',
            'report_file.*' => 'required|file|mimes:jpg,jpeg,png,bmp,pdf,xlsx',
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
