<?php

namespace lenal\offers\Observers;

use lenal\catalog\Jobs\OfferBrandAttachJob;
use lenal\catalog\Jobs\OfferBrandDetachJob;
use lenal\offers\Models\OfferBrand;

class OfferBrandsObserver
{
    public function created(OfferBrand $offerBrand)
    {
        OfferBrandAttachJob::dispatch($offerBrand->brand_id, $offerBrand->category_id, $offerBrand->offer_id);
    }

    public function deleted(OfferBrand $offerBrand)
    {
        OfferBrandDetachJob::dispatch($offerBrand->brand_id, $offerBrand->category_id, $offerBrand->offer_id);
    }
}
