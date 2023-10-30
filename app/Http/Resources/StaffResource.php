<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            "idno" => $this->idno,
            "pfno" => $this->pfno,
            "manno" => $this->manno,
            "photo" => $this->photo,
            "surname" => $this->surname,
            "first_name" => $this->first_name,
            "middle_name" => $this->middle_name,
            "box_no" => $this->box_no,
            "post_code" => $this->post_code,
            "town" => $this->town,
            "email" => $this->email,
            "phone" => $this->phone,
            "employer" => $this->employer,
            "gender" => $this->gender,
            "staff_role_id" => new StaffRoleResource($this->role),
            "admin_id" => $this->admin_id ? new AdminResource($this->admin) : null,
        ];
        // return parent::toArray($request);
    }
}
