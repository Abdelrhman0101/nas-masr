<?php

namespace App\Http\Requests;

use App\Models\CarModel;
use App\Models\City;
use App\Models\Governorate;
use App\Models\Make;
use Illuminate\Foundation\Http\FormRequest;
use NunoMaduro\Collision\Coverage;
use Illuminate\Validation\ValidationException;


class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'governorate_id' => $this->resolveId(Governorate::class, $this->governorate ?? $this->governorate_id),
            'city_id'        => $this->resolveId(City::class, $this->city ?? $this->city_id),
            'make_id'        => $this->resolveId(Make::class, $this->make ?? $this->make_id),
            'model_id'       => $this->resolveId(CarModel::class, $this->model ?? $this->model_id),
        ]);
    }

    private function resolveId(string $model, $value)
    {
        if (!$value) return null;
        if (is_numeric($value)) {
            return $value;
        }
        $id = $model::where('name', 'like', "%{$value}%")->value('id');

        if (!$id) {
            $field = strtolower(class_basename($model));
            throw ValidationException::withMessages([
                "{$field}" => [ucfirst($field) . " not found."],
            ]);
        }

        return $id;
    }

    public function rules(): array
    {
        return [
            'governorate_id' => 'nullable|exists:governorates,id',
            'city_id' => 'nullable|exists:cities,id',
            'make_id' => 'nullable|exists:makes,id',
            'model_id' => 'nullable|exists:models,id',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'kilometers' => 'nullable|integer|min:0',
            'type' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:100',
            'transmission' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'contact_phone' => 'required|string|max:20',
            'whatsapp_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'governorate_id.exists' => 'The selected governorate does not exist.',
            'city_id.exists' => 'The selected city does not exist.',
            'make_id.exists' => 'The selected car make does not exist.',
            'model_id.exists' => 'The selected car model does not exist.',
            'year.integer' => 'The year must be a valid number.',
            'year.min' => 'The year must be at least 1900.',
            'year.max' => 'The year cannot be in the future.',
            'kilometers.integer' => 'Kilometers must be a number.',
            'kilometers.min' => 'Kilometers cannot be negative.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
            'contact_phone.required' => 'Contact phone number is required.',
            'contact_phone.max' => 'Contact phone number cannot exceed 20 characters.',
            'whatsapp_phone.max' => 'WhatsApp phone number cannot exceed 20 characters.',
            'description.max' => 'Description cannot exceed 5000 characters.',
        ];
    }
}
