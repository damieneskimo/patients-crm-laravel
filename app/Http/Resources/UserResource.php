<?php

namespace App\Http\Resources;

use App\Http\Resources\NoteResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct($resource) {
        parent::__construct($resource);

        $this->withoutWrapping();
    }

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
            'email' => $this->email,
            'mobile' => $this->mobile,
            'gender' => $this->gender,
            'notes' => NoteResource::collection($this->notes)
        ];
    }
}
