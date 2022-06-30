<?php

namespace lenal\blog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource {

     public function toArray($request)
     {
         return [
             'id' => $this->id,
             'title' => $this->title,
             'slug' => $this->slug,
             'articles_count' => $this->articles->count(),
         ];
     }
}