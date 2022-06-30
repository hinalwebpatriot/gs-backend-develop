<?php

namespace lenal\blocks\Helpers;

use Illuminate\Http\Response;
use lenal\blocks\Models\DynamicPage;
use lenal\blocks\Resources\BlockCompleteLookResource;
use lenal\blocks\Resources\BlockOccasionResource;
use lenal\blocks\Resources\BlocksAdditionalResource;
use lenal\blocks\Resources\BlocksCertificateResource;
use lenal\blocks\Resources\BlocksCollection;
use lenal\blocks\Resources\BlocksDescriptonResource;
use lenal\blocks\Resources\BlocksGuideResource;
use lenal\blocks\Resources\BlocksIconResource;
use lenal\blocks\Resources\BlocksPromoResource;
use lenal\blocks\Resources\ContactsPageResource;
use lenal\blocks\Resources\FeedSliderCollection;
use lenal\blocks\Resources\RecommendProductsResource;
use lenal\blocks\Resources\StoryCustomJewelryResource;
use lenal\blocks\Resources\TopPicksResource;
use lenal\catalog\Resources\EngagementRingResource;

class Blocks
{
    public function page($page)
    {
        return DynamicPage::with([
            'recommendProducts.blockDiamonds'        => function ($query) {
                $query->withCalculatedPrice();
            },
            'recommendProducts.blockEngagementRings' => function ($query) {
                $query->withCalculatedPrice();
            },
            'recommendProducts.blockWeddingRings'    => function ($query) {
                $query->withCalculatedPrice();
            },
            'recommendProducts.blockProducts'        => function ($query) {
                $query->withCalculatedPrice();
            },
            'secondRingsSlider.blockEngagementRings' => function ($query) {
                $query->withCalculatedPrice()->take(10);
            },
            'occasionSpecial.blockEngagementRings'   => function ($query) {
                $query->withCalculatedPrice()->take(10);
            },
        ])
            ->wherePage($page)->first();
    }

    public function all($page)
    {
        $pageData = $this->page($page);
        return $pageData && $pageData->blocks
            ? new BlocksCollection(
                $pageData->blocks->makeHidden(
                    ['dynamic_page_id']
                )
            )
            : [];
    }

    public function certificate($page)
    {
        $pageData = $this->page($page);
        return $pageData && $pageData->certificateBlocks
            ? BlocksCertificateResource::collection($pageData->certificateBlocks)
            : [];
    }

    public function guide($page)
    {
        $pageData = $this->page($page);
        return $pageData && $pageData->guideBlock
            ? new BlocksGuideResource($pageData->guideBlock)
            : [];
    }

    public function description($page)
    {
        $pageData = $this->page($page);
        return $pageData && $pageData->descriptionBlock
            ? new BlocksDescriptonResource($pageData->descriptionBlock)
            : [];
    }

    public function diamondsDescription()
    {
        return $this->description('diamonds-detail');
    }


    public function promo($page)
    {
        $pageData = $this->page($page);
        return $pageData && !$pageData->promoBlocks->isEmpty()
            ? BlocksPromoResource::collection($pageData->promoBlocks)
            : [];
    }

    public function additionalInfo($page)
    {
        $pageData = $this->page($page);
        if (!$pageData) {
            return [];
        }

        return [
            'video_block' => $pageData->additionalInfoBlock
                ? new BlocksAdditionalResource($pageData->additionalInfoBlock) : null,
            'items_block' => $pageData->additionalInfoIcons
                ? new BlocksIconResource($pageData->additionalInfoIcons) : null,
        ];
    }

    public function slider($page)
    {
        $pageData = $this->page($page);
        if (!$pageData || $pageData->slider->isEmpty()) {
            return [];
        }
        $localeSliders = $pageData->slider
            ->filter(function ($item) {
                return $item->title == app()->getLocale();
            });
        return !$localeSliders->isEmpty()
            ? new FeedSliderCollection($localeSliders)
            : [];
    }

    public function contactsPage()
    {
        return new ContactsPageResource($this->page('contacts'));
    }

    public function recommendProducts($page)
    {
        $pageData = $this->page($page);

        return ($pageData && $pageData->recommendProducts)
            ? new RecommendProductsResource($pageData->recommendProducts)
            : response()->noContent();
    }

    public function completeLook()
    {
        $pageData = $this->page('diamonds-detail');
        return ($pageData && $pageData->completeLookBlock)
            ? new BlockCompleteLookResource($pageData->completeLookBlock)
            : response()->noContent();
    }

    public function occasionSlider()
    {
        $pageData = $this->page('homepage');
        return ($pageData && $pageData->occasionSpecial)
            ? new BlockOccasionResource($pageData->occasionSpecial)
            : response()->noContent();
    }

    public function secondRingsSlider()
    {
        $pageData = $this->page('diamonds-feed');
        if (!$pageData || !$pageData->secondRingsSlider) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
        $rings = $pageData->secondRingsSlider->blockEngagementRings;
        if ($rings->isEmpty()) { // block exists, but no items related
            return response()->noContent();
        }
        $rings = $rings->each(function ($item) {
            $item->id = $item->engagement_ring_id ?: $item->id;
        });

        return EngagementRingResource::collection($rings);
    }

    public function topPicks($page)
    {
        $pageData = $this->page($page);
        return ($pageData && $pageData->topPicks)
            ? new TopPicksResource($pageData->topPicks)
            : response()->noContent();
    }

    public function storyCustomJewelry()
    {
        $pageData = $this->page('homepage');
        return ($pageData && $pageData->storyCustomJewelry)
            ? new StoryCustomJewelryResource($pageData->storyCustomJewelry)
            : response()->noContent();
    }
}
