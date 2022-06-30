<?php

namespace lenal\catalog\Services\Feed;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Resources\ImageResource;
use Spatie\MediaLibrary\HasMedia\HasMedia;

abstract class Feed
{
    const FORMAT_XML = 'xml';
    const FORMAT_CSV = 'csv';

    protected $baseUrl;
    protected $buffer;
    protected $format;

    public function __construct()
    {
        $this->baseUrl = env('FRONTEND_URL') . '/';
        $this->buffer = new DataBufferWriter(public_path('feed/google.' . $this->format));
    }

    public function prepareString($str, $stripTags = false, $withCDATA = false)
    {
        if ($stripTags) {
            $str = strip_tags($str);
        }

        if ($withCDATA) {
            $str = '<![CDATA['.$str.']]>';
        } else {
            $str = htmlspecialchars($str, ENT_QUOTES);
        }

        return $str;
    }

    protected function createSlug($str)
    {
        return str_replace(' ', '-', $str);
    }

    protected function buildItems()
    {
        try {
            $this->weddingRings();
            $this->engagementRing();
        } catch (\Exception $e) {
            logger($e);
            $this->buffer->close();
        }
    }

    protected function weddingRings()
    {
        WeddingRing::query()->with(['ringCollection', 'ringStyle'])->chunk(1000, function(Collection $items) {
            $items->each(function(WeddingRing $item) {
                $this->addItem($this->format($item, 'Wedding Rings', 'wedding-images'));
            });
        });
    }

    protected function engagementRing()
    {
        EngagementRing::query()->with(['ringCollection', 'ringStyle', 'stoneShape'])->chunk(1000, function(Collection $items) {
            $items->each(function(EngagementRing $item) {
                $this->addItem($this->format($item, 'Engagement Rings', 'engagement-images'));
            });
        });
    }

    /**
     * @param Model|HasMedia $item
     * @param $category
     * @param $imageAlias
     * @return array
     */
    protected function format($item, $category, $imageAlias)
    {
        $photo = $item->getMedia($imageAlias)->first();
        $image = null;
        if ($photo) {
            $image = (new ImageResource($photo))->getFullUrl('medium-size');
        }

        return [
            'id' => $item->id,
            'url' => $this->baseUrl . $this->createSlug($item->h1) . '_' . $item->id . '_' . strtolower(substr($category, 0, 1)),
            'title' => $item->h1,
            'brand' => 'GS Diamonds',
            'description' => $item->defaultDescription(),
            'price' => $item->inc_price,
            'image' => $image,
            'category' => 200,
            'sku' => $item->sku,
        ];
    }

    abstract public function create();
    abstract protected function addItem(array $item);
}