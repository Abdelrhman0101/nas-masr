<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_slug' => ['required', 'string', 'max:100'],
            'field_name'    => ['required', 'string', 'max:100', 'unique:category_fields,field_name'],
            'display_name'  => ['required', 'string', 'max:150'],
            'type'          => ['required', 'string', 'in:string,int,decimal,bool,date,json'],
            'options'       => ['nullable', 'array'],   // لو dropdown
            'required'      => ['boolean'],
            'filterable'    => ['boolean'],
            'rules_json'    => ['nullable', 'array'],
            'sort_order'    => ['nullable', 'integer'],
            'is_active'     => ['boolean'],
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
