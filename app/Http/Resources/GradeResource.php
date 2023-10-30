<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $remark = $this->relationLoaded('remark') ? $this->remark->remark : $this->remark_id;
        return [
            "id" => $this->id,
            "min_score" => $this->min_score,
            "max_score" => $this->max_score,
            "grade" => $this->grade,
            "remark" => $remark,
        ];
        // return parent::toArray($request);
    }
}
