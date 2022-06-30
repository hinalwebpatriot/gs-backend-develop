<?php

namespace lenal\catalog\Models\Rings;

use Carbon\Carbon;
use Conner\Likeable\Likeable;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use lenal\blocks\Models\StaticBlock;
use lenal\catalog\Facades\CommonHelper;
use lenal\catalog\Jobs\RecalculateDiscountPricesJob;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Compare;
use lenal\catalog\Models\IPromocode;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Products\ProductFieldAssign;
use lenal\catalog\Models\ProductStates;
use lenal\catalog\Resources\ImageResource;
use lenal\catalog\Services\DeliveryTimeService;
use lenal\offers\Models\Offer;
use lenal\PriceCalculate\PriceRateCalculation;
use lenal\reviews\Models\Review;
use lenal\search\Traits\WeddingSearchable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer                                   $id
 * @property string                                    $slug
 * @property string                                    $sku
 * @property string                                    $group_sku
 * @property string                                    $title
 * @property string                                    $subtitle
 * @property string                                    $h1       calculation property
 * @property string                                    $h2       calculation property
 * @property number                                    $band_width
 * @property string                                    $gender
 * @property number                                    $raw_price
 * @property integer                                   $ring_collection_id
 * @property integer                                   $ring_style_id
 * @property string                                    $side_setting_type
 * @property integer                                   $min_ring_size_id
 * @property integer                                   $max_ring_size_id
 * @property integer                                   $metal_id
 * @property string                                    $item_name
 * @property number                                    $cost_price
 * @property number                                    $discount_price
 * @property string                                    $carat_weight
 * @property integer                                   $approx_stones
 * @property number                                    $inc_price
 * @property string                                    $description
 * @property string                                    $diamond_type
 * @property float                                     $thickness
 * @property string                                    $header
 * @property string                                    $sub_header
 * @property mixed                                     $old_calculated_price
 * @property mixed                                     $calculated_price
 * @property mixed                                     $delivery_period
 * @property int                                       $delivery_period_days
 * @property Carbon                                    $created_at
 * @property Carbon                                    $updated_at
 * @property int                                       $in_store
 * @property int                                       $is_top
 * @property bool                                      $is_active
 * @property int                                       $custom_sort
 * @property bool                                      $has_new_renders
 *
 * @property WeddingRingStyle                          $ringStyle
 * @property Metal                                     $metal
 * @property RingCollection                            $ringCollection
 * @property Offer[]|Collection                        $offers
 * @property ProductFieldAssign[]|Collection|MorphMany $customFields
 *
 * @property mixed                                     size_slug extra magic param
 */
class WeddingRing extends Model implements HasMedia, IPromocode, ProductStates
{
    use HasTranslations;
    use InteractsWithMedia;
    use Likeable;
    use PriceRateCalculation;
    use PivotEventTrait;
    use Searchable, WeddingSearchable {
        WeddingSearchable::searchableAs insteadof Searchable;
        WeddingSearchable::toSearchableArray insteadof Searchable;
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
    public $translatable = ['item_name', 'description', 'sub_header', 'header'];

    /**
     * @var array
     */
    protected $fillable = [
        'item_name',
        'slug',
        'sku',
        'band_width',
        'group_sku',
        'raw_price',
        'inc_price',
        'discount_price',
        'cost_price',
        'gender',
        'ring_style_id',
        'ring_collection_id',
        'side_setting_type',
        'min_ring_size_id',
        'max_ring_size_id',
        'metal_id',
        'carat_weight',
        'approx_stones',
        'thickness',
        'delivery_period',
        'delivery_period_days',
        'in_store',
        'is_active',
        'custom_sort',
        'has_new_renders'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'ring_style_id',
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

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->item_name;
    }

    /**
     * @return string
     */
    public function getH1Attribute()
    {
        $h1 = '';
        if ($this->ringCollection) {
            $h1 = $this->ringCollection->title.' ';
        }

        $h1 .= 'Wedding Ring';

        if ($this->gender == "male") {
            $h1 = "Infinity Mens Wedding Ring";
        }
        return $h1;
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
        return 'in '.$this->metal->title;
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
    public function ringStyle()
    {
        return $this->belongsTo('lenal\catalog\Models\Rings\WeddingRingStyle');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minRingSize()
    {
        return $this->belongsTo('lenal\catalog\Models\Rings\RingSize');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maxRingSize()
    {
        return $this->belongsTo('lenal\catalog\Models\Rings\RingSize');
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
        return $this->belongsToMany(StaticBlock::class, 'static_blocks_wedding_rings');
    }

    /**
     * @param  Media|null  $media
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
                    ->performOnCollections('img-wedding')
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
            ->performOnCollections('wedding-images')
            ->nonQueued();

        $this->addMediaConversion('medium-size')
            ->width(650)
            ->height(650)
            ->performOnCollections('wedding-images')
            ->nonQueued();

        $this->addMediaConversion('feed')
            ->width(250)
            ->height(250)
            ->performOnCollections('wedding-images')
            ->nonQueued();

        $this->addMediaConversion('feed_min')
            ->width(150)
            ->height(150)
            ->performOnCollections('wedding-images')
            ->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('img-wedding')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('wedding-images')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('wedding-video')
            ->singleFile()
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('wedding-images-3d')
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
            'ringStyle',
            'minRingSize',
            'maxRingSize',
            'metal',
        ]);
    }

    /**
     * @param  Builder  $query
     */
    public function scopeHasMediaFiles(Builder $query, $mediaCollection = null)
    {
        $mediaFilter = $mediaCollection
            ? ['model_type' => WeddingRing::class, 'collection_name' => $mediaCollection]
            : ['model_type' => WeddingRing::class];
        $items = Media
            ::where($mediaFilter)
            ->get()
            ->map(function ($mediaItem) {
                return $mediaItem->model_id;
            });
        return $query->whereIn('id', $items);
    }

    public function cachedImages()
    {
        return cache()->remember('wedding_images'.$this->id, 24 * 60, function () {
            $images = $this->getMedia('wedding-images');
            return [
                'preview' => $images->count() > 0 ? (new ImageResource($images->first()))->resolve() : null,
                'images'  => $images->count() > 0 ? ImageResource::collection($images)->resolve() : null,
            ];
        });
    }

    public function flushImageCache()
    {
        cache()->delete('wedding_images'.$this->id);
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
        return Str::lower(str_replace(' ', '-', $this->h1).'_'.$this->id.'_w');
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
        return Category::WEDDING;
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
            'img-wedding',
            CommonHelper::nameMediaConversion(
                $this->formats[1],
                $this->feedConversions[0][0],
                $this->feedConversions[0][1]
            )
        ) ?: '#';
    }
    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->withResourceRelation()->withCalculatedPrice();
    }
}

