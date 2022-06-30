<?php
namespace lenal\additional_content\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FAQResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
        ];
    }
}