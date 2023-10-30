<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $grades = $this->relationLoaded('grades') ? GradeResource::collection($this->grades) : null;
        $remarks = [
            "id" => $this->id,
            "remark" => $this->remark,
        ];
        if ($grades) {
            $remarks['grades'] = $grades;
        }
        return $remarks;
        // return parent::toArray($request);
    }
}
