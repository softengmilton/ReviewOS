<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['owner', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'plan' => ['required', 'in:free,starter,growth,business'],
        ];
    }
}
