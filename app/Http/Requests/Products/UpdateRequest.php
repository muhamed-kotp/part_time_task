<?php

namespace App\Http\Requests\Products;

use App\Traits\ValidationErrors;
use Illuminate\Foundation\Http\FormRequest;


class UpdateRequest extends FormRequest
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
            'name_ar' => 'nullable|string|max:100',
            'name_en' => 'nullable|string|max:100',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'img' => 'nullable|file|image|mimes:jpg,png,svg',
            'category_id'=> 'nullable|exists:categories,id',
        ];
    }

}
