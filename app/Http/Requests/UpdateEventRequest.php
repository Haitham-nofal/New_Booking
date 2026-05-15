<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
          return [
            //
        "title"=>"required|string|max:225"
        , "description"=>"nullable|string"
        , "location"=>"nullable|string"
        , "date"=>"required|date|min:0"
        , "available_seats"=>"required|integer",
        "category_id"=>"required|integer|exists:categories,id",
        "images"=>"nullable|array",
        "images*"=>"images|mimes:png,jpg,jpeg"
        ];
    }
}
