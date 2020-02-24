<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'hourly_rate' => Rule::requiredIf($request->user()->current_account == 'freelancer'),
            'tagline' => 'required',
            'city' => 'required',
            'address' => 'required',
            'mobile_phone' => 'required',
            'country' => 'required',
            'category' => Rule::requiredIf($request->user()->current_account == 'freelancer'),
            'skills' => Rule::requiredIf($request->user()->current_account == 'freelancer'),
            'presentation' => Rule::requiredIf($request->user()->current_account == 'freelancer'),
        ];
    }
}
