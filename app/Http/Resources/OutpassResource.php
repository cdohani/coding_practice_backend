<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutpassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'outpass_date' => $this->outpass_date,
            'outpass_from' => $this->outpass_from,
            'outpass_to' => $this->outpass_to,
            'student_name'=> $this->students->name,
            'status'=>$this->status,
        ];
    }
}
