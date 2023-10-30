<?php

namespace App\Http\Resources;

use App\Http\Resources\TermResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeVoteHeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'fee' => $this->relationLoaded('fee') ? new FeeResource($this->fee) : $this->fee_id,
            'vote_head' => $this->vote_head,
            'share' => $this->share,
        ];
        if ($this->relationLoaded('fee')) {
            $data['amount'] = $this->share * $this->fee->amount;
        }
        return $data;
        // return parent::toArray($request);
    }
}
