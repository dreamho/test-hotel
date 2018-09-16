<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Party
 * @package App\Http\Resources
 */
class Party extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'tags' => $this->tags,
            'capacity' => $this->capacity,
            'length' => $this->length,
            'image' => $this->image,
            'started' => $this->started,
            //'joined' => $this->isJoined($request)
        ];
    }
}
