<?php

namespace lenal\search\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use lenal\search\Facades\SearchHelper;

/**
 * Class DetailSearchResource
 *
 * @package lenal\catalog\Resources
 */
class DetailSearchCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'       => SearchHelper::wrapViaCollection($this->collection),
            'pagination' => [
                'total'        => $this->resource->total(),
                'count'        => $this->resource->count(),
                'last_page'    => $this->resource->lastPage(),
                'per_page'     => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
            ]
        ];
    }
}
