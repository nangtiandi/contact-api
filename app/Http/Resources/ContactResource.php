<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->photo === null) {
            $this->photo = asset('default.png');
        }else{
            $this->photo = asset('storage/profile/'.$this->photo);
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'photo' => $this->photo,
            'time' => $this->created_at->format('d M Y')
        ];
    }
}
