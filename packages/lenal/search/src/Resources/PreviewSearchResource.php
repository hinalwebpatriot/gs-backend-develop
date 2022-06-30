<?php

namespace lenal\search\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use lenal\blog\Resources\ArticlePreviewResource;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\search\Facades\SearchHelper;

/**
 * Class PreviewSearchResource
 *
 * @package lenal\catalog\Resources
 */
class PreviewSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $preview_search_list = collect();

        if ($this->resource) {
            foreach ($this->resource as $resourceName => $items) {
                if ($resourceName == 'blog') {
                    continue;
                }

                foreach ($items as $item) {
                    $preview_search_list->push($item);
                }
            }
        }

        $preview_search_list = $preview_search_list->sortByDesc(function (Model $model) {
                return $model->created_at;
            })
            ->slice(0, 4);

        return [
            'blog'     => ArticlePreviewResource::collection($this->resource['blog']),
            'products' => $preview_search_list
                ->map(function (Model $model) {
                    return SearchHelper::wrapViaResource($model);
                }),
        ];
    }
}
