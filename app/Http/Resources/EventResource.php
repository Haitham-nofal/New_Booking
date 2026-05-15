<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
                "title" =>$this->title
                , "description"=>$this->description
                , "location"=>$this->location
                , "date"=>$this->date
                , "available_seats"=>$this->available_seats,
                "image"=> $this->getMedia("main_image")->map(function($media){
                    return $media->getUrl();
                }),
                "category"=> new CategoryResource($this->whenLoaded("category")),
        ];
    }
}
