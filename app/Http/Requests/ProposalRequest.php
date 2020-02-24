<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProposalRequest extends FormRequest
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
            'proposal_type' => 'required',
            'hourly_amount' => 'required_if:proposal_type,==,Hourly Rate|nullable|numeric|min:5|max:150',
            'fixed_amount' => 'required_if:proposal_type,==,Fixed Price|nullable|numeric|min:5|max:100000',
            'cover_letter' => 'required',
        ];
    }
}
