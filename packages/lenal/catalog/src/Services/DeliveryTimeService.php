<?php

namespace lenal\catalog\Services;


use Illuminate\Support\Facades\Log;
use lenal\catalog\Models\DeliverySchema;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\{Category, Product};
use lenal\catalog\Models\Rings\{EngagementRing, WeddingRing};

class DeliveryTimeService
{
    const DEFAULT_DELIVERY_PERIOD              = 4;
    const DEFAULT_DIAMOND_DELIVERY_PERIOD_DAYS = 21;

    /**
     * @param  int     $dpWeeks
     * @param  int     $dpDays
     * @param  string  $category
     * @param  int     $metalId
     * @param  null    $sku
     * @param  null    $styleId
     * @return string
     */
    public function estimateTimeMessage($dpWeeks, $dpDays, $category, $metalId, $sku = null, $styleId = null)
    {
        $deliverySchema = $this->deliveryPeriod(
            $dpWeeks,
            $dpDays,
            $category,
            $metalId,
            false,
            $sku,
            $styleId
        );
        $result = $deliverySchema->deliveryPeriodParams();


        if ($result['period'] > 0) {
            $translate = [
                'period' => $result['period']
            ];

            switch ($result['unit']) {
                case 'days':
                    $translate['unit'] = $result['period'] > 1 ? 'days' : 'day';
                    break;
                case 'weeks':
                    $translate['unit'] = $result['period'] > 1 ? 'weeks' : 'week';
                    break;
            }
            return trans('api.estimate-delivery-time', $translate);
        }

        return '';
    }

    public function makeTimeMessage($deliveryPeriodParams)
    {
        if ($deliveryPeriodParams['period'] > 0) {
            $translate = [
                'period' => $deliveryPeriodParams['period']
            ];

            switch ($deliveryPeriodParams['unit']) {
                case 'days':
                    $translate['unit'] = $deliveryPeriodParams['period'] > 1 ? 'days' : 'day';
                    break;
                case 'weeks':
                    $translate['unit'] = $deliveryPeriodParams['period'] > 1 ? 'weeks' : 'week';
                    break;
            }
            return trans('api.estimate-delivery-time', $translate);
        }

        return '';
    }

    /**
     * @param  int     $dpWeeks
     * @param  int     $dpDays
     * @param  string  $category
     * @param  int     $metalId
     * @param  null    $sku
     * @param  null    $styleId
     * @return array
     */
    public function getDeliveryTime($dpWeeks, $dpDays, $category, $metalId, $sku = null, $styleId = null)
    {
        $deliverySchema = $this->deliveryPeriod($dpWeeks, $dpDays, $category, $metalId, false, $sku, $styleId);
        $params = $deliverySchema->deliveryPeriodParams();
        return array_merge(['text' => $this->makeTimeMessage($params)], $params);
    }

    /**
     * @param        $dpWeeks
     * @param        $dpDays
     * @param        $category
     * @param        $metalId
     * @param  bool  $withDiamond
     * @param  null  $sku
     * @param  null  $styleId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|DeliverySchema|object
     */
    public function deliveryPeriod(
        $dpWeeks,
        $dpDays,
        $category,
        $metalId,
        $withDiamond = false,
        $sku = null,
        $styleId = null
    ) {
        if (!$dpWeeks && !$dpDays) {
            $deliverySchema = DeliverySchema::findDeliveryPeriod($category, $metalId, $withDiamond, $sku, $styleId);
            if ($deliverySchema) {
                return $deliverySchema;
            } else {
                $deliverySchema = new DeliverySchema();
                if ($category == Category::DIAMONDS) {
                    $deliverySchema->delivery_period = self::DEFAULT_DIAMOND_DELIVERY_PERIOD_DAYS;
                } else {
                    $deliverySchema->delivery_period_wk = self::DEFAULT_DELIVERY_PERIOD;
                }

                return $deliverySchema;
            }
        }

        $deliverySchema = new DeliverySchema();
        $deliverySchema->delivery_period_wk = $dpWeeks;
        $deliverySchema->delivery_period = $dpDays;
        return $deliverySchema;
    }

    /**
     * @param  \Illuminate\Support\Collection  $products
     * @return DeliverySchema
     */
    public function maxForProductCollection($products)
    {
        $hasDiamond = count(array_filter($products->all(), function ($product) {
                return is_a($product, Diamond::class);
            })) > 0;

        $deliveries = [];

        /** @var WeddingRing|EngagementRing|Product|Diamond $product */
        foreach ($products as $product) {
            $deliveries[] = $this->deliveryPeriod(
                $product->delivery_period ?? null,
                is_a($product, Diamond::class) ? $product->delivery_period_days : null,
                $product->getCategorySlug(),
                $product->metal_id ?? null,
                is_a($product, EngagementRing::class) ? $hasDiamond : false,
                $product->sku,
                is_a($product, EngagementRing::class) ? $product->ring_style_id : null
            );
        }

        if ($deliveries) {
            usort($deliveries, function (DeliverySchema $schema1, DeliverySchema $schema2) {
                return $schema2->maxDays() <=> $schema1->maxDays();
            });

            return $deliveries[0];
        } else {
            $deliverySchema = new DeliverySchema();
            $deliverySchema->delivery_period_wk = self::DEFAULT_DELIVERY_PERIOD;
            return $deliverySchema;
        }
    }
}