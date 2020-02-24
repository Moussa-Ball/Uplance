<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'account_type' => $this->account_type,
            'current_account' => ($this->current_account) ? $this->current_account : "",
            'avatar' => $this->avatar,
            'hourly_rate' => $this->hourly_rate,
            'tagline' => $this->tagline,
            'city' => $this->city,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'skills' => $this->skills ?? [],
            'mobile_phone' => $this->mobile_phone,
            'presentation' => $this->presentation,
            'password' => ($this->password) ? true : false
        ];
    }
}
