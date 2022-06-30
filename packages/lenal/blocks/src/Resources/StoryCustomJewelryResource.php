<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 3/22/19
 * Time: 4:13 PM
 */

namespace lenal\blocks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Resources\VideoResource;

class StoryCustomJewelryResource extends JsonResource
{
    public function toArray($request)
    {
        $images360 = $this->getMedia('static-images-3d')->count()
            ? $this->getMedia('static-images-3d')->map(function ($media) { return $media->getFullUrl(); })
            : null;
        return [
            'title1' => $this->title,
            'title2' => $this->subtitle,
            'title3' => $this->text,
            'video'  => new VideoResource($this->getMedia('video')->first()),
            'images_360'  => $images360
        ];
    }
}