<?php

namespace lenal\catalog\Repositories;


use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Rings\EngagementRingStyle;
use lenal\catalog\Models\Rings\WeddingRingStyle;

class RingStyleRepository
{
    const TYPE_WEDDING = 'wedding';
    const TYPE_ENGAGEMENT = 'engagement';
    /**
     * @var \Illuminate\Support\Collection
     */
    private $collection = [];
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
        $this->loadCollection();
    }

    /**
     * @return string|Model
     */
    private function getClass()
    {
        return ($this->type == self::TYPE_WEDDING) ? WeddingRingStyle::class : EngagementRingStyle::class;
    }

    public function get($id)
    {
        return $this->collection->get($id);
    }

    private function loadCollection()
    {
        $data = cache()->remember('ring-style-' . $this->type, 60, function() {
            $collection = [];
            /** @var WeddingRingStyle|EngagementRingStyle $style */
            foreach ($this->getClass()::query()->get()->keyBy('id') as $style) {

                $image = $style->getMedia('image')->first();
                $imageHover = $style->getMedia('image-hover')->first();

                $collection[$style->id] = [
                    'id' => $style->id,
                    'title' => $style->title,
                    'slug' => $style->slug,
                    'image' => $image ? $image->getFullUrl() : null,
                    'image_hover' => $imageHover ? $imageHover->getFullUrl() : null,
                    'gender' => $style->gender,
                ];
            }

            return $collection;
        });

        $this->collection = collect($data);
    }
}