<?php

namespace App\Http\Resources;

use App\Http\Resources\TermResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
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
            'term' => $this->relationLoaded() ? new TermResource($this->term) : $this->term_id,
            'course' => $this->relationLoaded() ? new CourseResource($this->course) : $this->course_id,
            'amount' => $this->amount,
        ];
        // return parent::toArray($request);
    }
}
