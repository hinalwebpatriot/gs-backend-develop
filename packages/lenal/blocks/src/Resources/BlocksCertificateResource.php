<?php

namespace lenal\blocks\Resources;

class BlocksCertificateResource extends BlocksResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $resource = parent::toArray($request);
        return [
            'id' => $this->id,
            'text' => $this->text,
            'image' => $resource['image'],
            'file' => $this->getLocalizedMedia(parent::toArray($request), 'certificate_file')
        ];
    }
}