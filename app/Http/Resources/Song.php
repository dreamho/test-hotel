<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Song
 * @package App\Http\Resources
 */
class Song extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'artist' => $this->artist,
            'track' => $this->track,
            'link' => $this->link,
            'length' => $this->length,
        ];
    }
}
