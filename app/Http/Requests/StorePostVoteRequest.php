<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostVoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('vote', $this->route('post')) ?? false;
    }

    public function rules(): array
    {
        return [
            'direction' => ['nullable', 'in:up,down'],
        ];
    }
}
