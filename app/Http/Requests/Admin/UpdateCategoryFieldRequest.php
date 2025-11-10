<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('categoryField')?->id ?? null;

        return [
            'category_slug' => ['sometimes', 'string', 'max:100'],
            'field_name' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('category_fields', 'field_name')->ignore($id),
            ],
            'display_name' => ['sometimes', 'string', 'max:150'],
            'type' => ['sometimes', 'string', 'in:string,int,decimal,bool,date,json'],
            'options' => ['nullable', 'array'],
            'required' => ['sometimes', 'boolean'],
            'filterable' => ['sometimes', 'boolean'],
            'rules_json' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function prepareForValidation(): void
    {
        $data = $this->all();

        if (isset($data['options']) && is_string($data['options'])) {
            $decoded = json_decode($data['options'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['options'] = $decoded;
            } else {
                $data['options'] = [$data['options']];
            }
        }

        $this->replace($data);
    }
}
