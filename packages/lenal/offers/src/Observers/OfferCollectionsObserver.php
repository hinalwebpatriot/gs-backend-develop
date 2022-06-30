<?php

namespace lenal\offers\Observers;

use lenal\catalog\Jobs\OfferCollectionAttachJob;
use lenal\catalog\Jobs\OfferCollectionDetachJob;
use lenal\offers\Models\OfferCollection;

class OfferCollectionsObserver
{
    public function created(OfferCollection $offerCollection)
    {
        OfferCollectionAttachJob::dispatch($offerCollection->collection_id, $offerCollection->offer_id);
    }

    public function deleted(OfferCollection $offerCollection)
    {
        OfferCollectionDetachJob::dispatch($offerCollection->collection_id, $offerCollection->offer_id);
    }
}
