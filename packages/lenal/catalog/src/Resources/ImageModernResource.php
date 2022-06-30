<?php

namespace lenal\catalog\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use lenal\catalog\Facades\CommonHelper;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class EngagementFeedImageResource
 * @mixin Media
 */
class ImageModernResource extends JsonResource
{

    private array $formats;
    private array $conversions;

    public function __construct($resource, $formats, $conversions)
    {
        parent::__construct($resource);
        $this->formats = $formats;
        $this->conversions = $conversions;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'origin' => $this->getFullUrl(),
        ];
        foreach ($this->formats as $format) {
            $images = [];
            foreach ($this->conversions as $conversion) {
                $size = CommonHelper::sizeMediaConversion($conversion[0], $conversion[1]);
                $name = CommonHelper::nameMediaConversion($format, $conversion[0], $conversion[1]);
                $images[$size] = $this->getFullUrl($name);
            }
            $result[$format] = $images;
        }
        return $result;
    }
}

