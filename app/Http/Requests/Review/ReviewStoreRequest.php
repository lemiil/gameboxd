<?php

namespace App\Http\Requests\Review;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->input('status', 'planned'),
            'liked' => $this->boolean('liked', false),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|between:0,10',
            'text' => 'nullable|string|max:16384',
            'liked' => 'nullable|boolean',
            'status' => 'nullable|in:planned,played,dropped,completed,shelved',
            'platform' => 'nullable|exists:platforms,name',
            'game_id' => 'required|exists:games,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
