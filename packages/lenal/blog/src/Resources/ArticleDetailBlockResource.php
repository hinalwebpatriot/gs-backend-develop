<?php

namespace lenal\blog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailBlockResource extends JsonResource {

     public function toArray($request)
     {
         $image = $this->video? null: $this->getMedia('main_image_detail')->first();

         return [
             'id' => $this->id,
             'title' => $this->title? : null,
             'text' => $this->text,
             'video' => $this->video? : null,
             'image' => $image
                 ? $image->getFullUrl()
                 : null
         ];
     }
}