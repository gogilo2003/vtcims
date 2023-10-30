<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $staff = $this->relationLoaded('staff') ? new StaffResource($this->staff) : null;
        return [
            "id" => $this->id,
            "code" => $this->code,
            "name" => $this->name,
            "staff" => $staff,
        ];
        // return parent::toArray($request);
    }
}
