<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'project_name' => $this->project_name,
            'category' => ($this->categories()->first()) ? $this->categories()->first()->name : "",
            'minimum' => $this->minimum,
            'maximum' => $this->maximum,
            'location' => $this->location,
            'project_type' => $this->project_type,
            'skills' => $this->skills,
            'description' => $this->description,
        ];
    }
}
