<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('comment', $this->route('post')) ?? false;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'parent_id' => [
                'nullable',
                Rule::exists('post_comments', 'id')->where('post_id', $this->route('post')?->id),
            ],
        ];
    }
}
