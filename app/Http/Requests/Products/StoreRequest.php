<?php

namespace App\Http\Requests\Products;

use App\Traits\ValidationErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreRequest extends FormRequest
{
    use ValidationErrors;
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
            'name_ar' => 'required|string|max:100',
            'name_en' => 'required|string|max:100',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'img' => 'required|image|mimes:jpg,png,svg',
            'category_id'=> 'required|exists:categories,id',
        ];
    }

}
