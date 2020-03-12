<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
    public function rules()
    {
        return [
            'contract_title' => 'required|string',
            'hourly_rate' => 'nullable',
            'milestones' => 'nullable',
            'total_amount' => 'nullable',
            'offer_type' => 'required|string',
            'due_date' => 'nullable',
            'description' => 'required',
        ];
    }
}
