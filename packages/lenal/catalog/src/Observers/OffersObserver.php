<?php

namespace lenal\catalog\Observers;

use lenal\catalog\Jobs\PrepareDiscountPricesRecalculateJob;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\offers\Models\Offer;

/**
 * Class OffersObserver
 *
 * @package lenal\catalog\Observers
 */
class OffersObserver
{
    /**
     * @param Offer $offer
     */
    public function saved(Offer $offer)
    {
        $this->firePriceRecalculation($offer);
    }

    public function deleted(Offer $offer)
    {
        $offer->collections()->detach();
        $offer->brands()->detach();

        $this->firePriceRecalculation(null);
    }

    /**
     * @param Offer|null $offer
     */
    private function firePriceRecalculation(?Offer $offer)
    {
        PrepareDiscountPricesRecalculateJob::dispatch([
            EngagementRing::class,
            WeddingRing::class,
            Product::class,
        ], $offer);
    }
}
