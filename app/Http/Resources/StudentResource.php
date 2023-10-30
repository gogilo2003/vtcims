<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'id' => $this->id,
            'admission_no' => $this->admission_no,
            'name' => $this->name,
            'class' => $this->intake_name,
            'course' => $this->course_name,
            'sponsor' => $this->sponsor_name,
            'program' => $this->program_name
        ];
        // return parent::toArray($request);
    }
}
