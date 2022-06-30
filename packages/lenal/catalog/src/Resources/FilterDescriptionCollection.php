<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 2/8/19
 * Time: 3:41 PM
 */

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FilterDescriptionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $response = [];
        $this->collection
            ->each(function ($itemsCollection, $key) use (&$response) {
                    $itemsCollection->each(function ($item) use (&$response, $key) {
                        $response[$key][$item->slug] = $item->video_link;
                    });
                }
            );
        return $response;
    }
}