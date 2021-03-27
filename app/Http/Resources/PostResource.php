<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'data' => [
                'id' => $this->id,
                'user' => new UserResource($this->user),
                'caption' => $this->caption,
                'location' => $this->location,
            ],
            'links' => [
                'self' => route('posts.show', $this),
            ]
        ];
    }
}