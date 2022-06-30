<?php

namespace lenal\blocks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\social\Facades\Social;

class ContactsPageResource extends JsonResource {

    public function toArray($request)
    {
        return [
            'tags' => BlockLinkResource::collection($this->tagLinks),
            'schedule' => new BlockSimpleTextResource($this->textBlock),
            'supportContacts' => Social::getSupportContacts()
        ];
    }
}
