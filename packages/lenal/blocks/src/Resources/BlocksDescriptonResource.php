<?php

namespace lenal\blocks\Resources;

use Illuminate\Support\Facades\Lang;

class BlocksDescriptonResource extends BlocksResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text
        ];
    }
}