<?php

namespace lenal\catalog\Models\Rings;

use Carbon\Carbon;
use Conner\Likeable\Likeable;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use lenal\blocks\Models\StaticBlock;
use lenal\catalog\Facades\CommonHelper;
use lenal\catalog\Jobs\RecalculateDiscountPricesJob;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Compare;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\catalog\Models\IPromocode;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Products\ProductFieldAssign;
use lenal\catalog\Models\ProductStates;
use lenal\catalog\Resources\ImageResource;
use lenal\catalog\Services\DeliveryTimeService;
use lenal\offers\Models\Offer;
use lenal\PriceCalculate\PriceRateCalculation;
use lenal\reviews\Models\Review;
use lenal\search\Traits\EngagementSearchable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer                                   $id
 * @property string                                    $slug
 * @property string                                    $sku
 * @property string                                    $title
 * @property string                                    $subtitle
 * @property string                                    $h1
 * @property string                                    $h2
 * @property string                                    $group_sku
 * @property number                                    $band_width
 * @property number                                    $raw_price
 * @property integer                                   $ring_collection_id
 * @property integer                                   $ring_style_id
 * @property integer                                   $stone_shape_id
 * @property number                                    $stone_size
 * @property string                                    $setting_type
 * @property string                                    $side_setting_type
 * @property integer                                   $min_ring_size_id
 * @property integer                                   $max_ring_size_id
 * @property integer                                   $metal_id
 * @property string                                    $item_name
 * @property number                                    $cost_price
 * @property number                                    $discount_price
 * @property string                                    $carat_weight
 * @property string                                    $average_ss_colour
 * @property string                                    $average_ss_clarity
 * @property integer                                   $approx_stones
 * @property number                                    $inc_price
 * @property string                                    $header
 * @property string                                    $sub_header
 * @property string                                    $description
 * @property mixed                                     $old_calculated_price
 * @property mixed                                     $calculated_price
 * @property mixed                                     $delivery_period
 * @property int                                       $delivery_period_days
 * @property int                                       $disable_constructor
 * @property Carbon                                    $created_at
 * @property Carbon                                    $updated_at
 * @property int                                       $in_store
 * @property int                                       $is_top
 * @property string                                    $gender
 * @property bool                                      $is_active
 * @property double                                    $min_stone_carat
 * @property double                                    $max_stone_carat
 * @property int                                       $custom_sort
 * @property bool                                      $has_new_renders
 * @property bool                                      $is_best_for_merchant
 *
 * @property EngagementRingStyle                       $ringStyle
 * @property Shape                                     $stoneShape
 * @property Metal                                     $metal
 * @property RingCollection                            $ringCollection
 * @property Offer[]|Collection                        $offers
 * @property ProductFieldAssign[]|Collection|MorphMany $customFields
 * @property Media                                     $firstMedia
 *
 * @property mixed                                     $size_slug extra magic
 */
class EngagementRing extends Model implements HasMedia, IPromocode, ProductStates
{
    use HasTranslations;
    use Likeable;
    use PriceRateCalculation;
    use InteractsWithMedia;
    use PivotEventTrait;
    use Searchable, EngagementSearchable {
        EngagementSearchable::searchableAs insteadof Searchable;
        EngagementSearchable::toSearchableArray insteadof Searchable;
    }

    const BASE_CURRENCY = 'AUD';

    // форматы перекодировки изображений
    public array $formats = [Manipulations::FORMAT_WEBP, Manipulations::FORMAT_JPG];
    // размеры изображений
    public array $feedConversions = [
        [225, 225],
        [450, 450], //x2
        [140, 140],
        [280, 280], //x2
    ];
    // карточка товара
    public array $cardConversions = [
        [225, 225],
        [450, 450],//x2
        [900, 900],//x2
        [100, 100],
        [200, 200],//x2
    ];

    private $base_currency = 'AUD';

    /**
     * @var array
     */
    public $translatable = ['item_name', 'header', 'sub_header', 'description'];

    /**
     * @var array
     */
    protected $fillable = [
        'item_name',
        'slug',
        'sku',
        'band_width',
        'raw_price',
        'inc_price',
        'discount_price',
        'cost_price',
        'group_sku',
        'stone_size',
        'setting_type',
        'side_setting_type',
        'ring_style_id',
        'stone_shape_id',
        'ring_collection_id',
        'min_ring_size_id',
        'max_ring_size_id',
        'metal_id',
        'carat_weight',
        'average_ss_colour',
        'average_ss_clarity',
        'approx_stones',
        'delivery_period',
        'delivery_period_days',
        'disable_constructor',
        'in_store',
        'gender',
        'is_active',
        'min_stone_carat',
        'max_stone_carat',
        'custom_sort',
        'has_new_renders',
        'is_best_for_merchant',
    ];

    protected $hidden = [
        'ring_style_id',
        'stone_shape_id',
        'ring_collection_id',
        'min_ring_size_id',
        'max_ring_size_id',
        'metal_id',
    ];

    protected $estimateDeliveryTime = [];

    protected static function boot()
    {
        parent::boot();

        static::pivotAttached(function ($model, $relationName) {
            if ($relationName == 'offers') {
                RecalculateDiscountPricesJob::dispatch($model);
            }
        });

        static::pivotUpdated(function ($model, $relationName) {
            if ($relationName == 'offers') {
                RecalculateDiscountPricesJob::dispatch($model);
            }
        });

        static::pivotDetached(function ($model, $relationName) {
            if ($relationName == 'offers') {
                RecalculateDiscountPricesJob::dispatch($model);
            }
        });
    }

    public function firstMedia(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', 'img-engagement')
            ->orderBy('order_column');
    }

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->item_name.' '.$this->stoneShape->title;
    }

    /**
     * @return string
     */
    public function getH1Attribute()
    {
        $h1 = '';
        if (isset($this->ringCollection->title)) {
            $h1 .= $this->ringCollection->title.' ';
        }
        if (isset($this->ringStyle->title)) {
            $h1 .= $this->ringStyle->title.' ';
        }
        return $h1."Diamond Engagement Ring";
    }

    /**
     * @return string
     */
    public function getH2Attribute()
    {
        return ($this->sub_header) ?: $this->defaultDescription();
    }

    public function defaultDescription()
    {
        $desc = 'in ';

        if (isset($this->metal->title)) {
            $desc .= $this->metal->title.' with ';
        }

        if (isset($this->stoneShape->title)) {
            $desc .= $this->stoneShape->title.' ';
        }

        return $desc."Centre Stone";
    }

    public function getSubTitleAttribute()
    {
        return $this->ringStyle
            ? $this->ringStyle->title.', '.$this->metal->title
            : $this->metal->title;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ringCollection()
    {
        return $this->belongsTo('lenal\catalog\Models\Rings\RingCollection');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stoneShape()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Shape');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ringStyle()
    {
        return $this->belongsTo('lenal\catalog\Models\Rings\EngagementRingStyle');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minRingSize()
    {
        return $this->belongsTo(RingSize::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maxRingSize()
    {
        return $this->belongsTo(RingSize::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metal()
    {
        return $this->belongsTo('lenal\catalog\Models\Rings\Metal');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staticBlocks()
    {
        return $this->belongsToMany(StaticBlock::class, 'static_blocks_engagement_rings');
    }

    /**
     * @param  Media|null  $media
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $conversions = collect()->merge($this->feedConversions)->merge($this->cardConversions)->unique();

        foreach ($this->formats as $format) {
            foreach ($conversions as $conversion) {
                $this->addMediaConversion(
                    CommonHelper::nameMediaConversion($format, $conversion[0], $conversion[1])
                )
                    ->width($conversion[0])
                    ->height($conversion[1])
                    ->format($format)
                    ->optimize()
                    ->performOnCollections('img-engagement')
                    ->nonQueued();
            }
        }

        /**
         * Старые форматы изображений
         * @TODO после перехода и перевода на новые форматы нужно будет эти изображения удалить
         *       написать скрипт по удалению
         */
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->optimize()
            ->performOnCollections('engagement-images')
            ->nonQueued();

        $this->addMediaConversion('medium-size')
            ->width(650)
            ->height(650)
            ->optimize()
            ->performOnCollections('engagement-images')
            ->nonQueued();

        $this->addMediaConversion('feed')
            ->width(255)
            ->height(255)
            ->optimize()
            ->performOnCollections('engagement-images')
            ->nonQueued();

        $this->addMediaConversion('feed_min')
            ->width(150)
            ->height(150)
            ->optimize()
            ->performOnCollections('engagement-images')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('img-engagement')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('engagement-images')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('engagement-images-3d')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('engagement-video')
            ->singleFile()
            ->useDisk(config('filesystems.cloud'));
    }

    public function compares()
    {
        return $this->morphMany(Compare::class, 'product');
    }

    public function carts()
    {
        return $this->morphMany(CartItem::class, 'product');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function offers()
    {
        return $this->morphToMany(Offer::class, 'model', 'offer_relations');
    }

    /**
     * @param  Builder  $query
     */
    public function scopeWithResourceRelation(Builder $query)
    {
        $query->with([
            'ringCollection',
            'stoneShape',
            'ringStyle',
            'minRingSize',
            'maxRingSize',
            'metal',
        ]);
    }

    public function cachedImages()
    {
        return cache()->remember('engagement_images'.$this->id, 24 * 60, function () {
            $images = $this->getMedia('engagement-images');
            return [
                'preview' => $images->count() > 0 ? (new ImageResource($images->first()))->resolve() : null,
                'images'  => $images->count() > 0 ? ImageResource::collection($images)->resolve() : null,
            ];
        });
    }

    public function flushImageCache()
    {
        cache()->delete('engagement_images'.$this->id);
    }

    public function findOffers()
    {
        return $this->offers->map(function ($offer) {
            return [
                'id'    => $offer->id,
                'title' => $offer->title,
                'slug'  => $offer->slug,
            ];
        })->toArray();
    }

    public function getUri()
    {
        return Str::lower(str_replace(' ', '-', $this->h1).'_'.$this->id.'_e');
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getCollectionId()
    {
        return $this->ring_collection_id;
    }

    public function getCategorySlug()
    {
        return Category::ENGAGEMENT;
    }

    /**
     * @return mixed
     * @deprecated use getEstimateDeliveryTime()
     */
    public function estimateDeliveryTime()
    {
        return $this->getEstimateDeliveryTime()['text'];
    }

    public function getEstimateDeliveryTime()
    {
        if (!$this->estimateDeliveryTime) {
            $this->estimateDeliveryTime = app(DeliveryTimeService::class)
                ->getDeliveryTime(
                    $this->delivery_period,
                    $this->delivery_period_days,
                    $this->getCategorySlug(),
                    $this->metal_id,
                    $this->sku
                );
        }

        return $this->estimateDeliveryTime;
    }

    public function isAvailable()
    {
        return !!$this->id;
    }

    public function customFields()
    {
        return $this->morphMany(ProductFieldAssign::class, 'product_field', 'product_type', 'product_id');
    }

    public function previewImageUrl()
    {
        return $this->getFirstMediaUrl(
            'img-engagement',
            CommonHelper::nameMediaConversion(
                $this->formats[1],
                $this->feedConversions[0][0],
                $this->feedConversions[0][1]
            )
        ) ?: '#';
    }
}

