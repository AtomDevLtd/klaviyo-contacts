<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->contactList->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'last_name' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'email' => [
                'required',
                'email',
            ],
            'title' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'organization' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ];
    }
}
