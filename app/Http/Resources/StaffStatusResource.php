<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $staff = $this->relationLoaded('staff') ? StaffResource::collection($this->staff) : null;
        return [
            'name' => $this->name,
            'description' => $this->description,
            'staff' => $staff,
        ];
    }
}
