<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => '|required|string|max:255',
            'image' => 'nullable|image',
            'chairman' => 'required|string|max:255',
            'vice_chairman' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'sort_order' => 'required|integer|min:0|unique:candidates,sort_order,' . $this->route('candidate'),
        ];
    }
}
