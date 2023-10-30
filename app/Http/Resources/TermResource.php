<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "year" => $this->year,
            "start_date" => date_create($this->start_date)->format('j-M-Y'),
            "end_date" => date_create($this->end_date)->format('j-M-Y'),
        ];
        // return parent::toArray($request);
    }
}
