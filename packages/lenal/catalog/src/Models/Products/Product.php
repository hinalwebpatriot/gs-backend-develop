<?php

namespace lenal\catalog\Models\Products;

use Conner\Likeable\Likeable;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use lenal\catalog\Facades\CommonHelper;
use lenal\catalog\Jobs\RecalculateDiscountPricesJob;
use lenal\catalog\Models\IPromocode;
use lenal\catalog\Models\ProductStates;
use lenal\catalog\Resources\ImageResource;
use lenal\catalog\Services\DeliveryTimeService;
use lenal\PriceCalculate\PriceRateCalculation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property string $slug
 * @property string $sku
 * @property string $group_sku
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $metal_id
 * @property integer $style_id
 * @property integer $shape_id
 * @property string $gender
 * @property mixed $item_name
 * @property mixed $description
 * @property mixed $header
 * @property mixed $sub_header
 * @property number $raw_price
 * @property number $cost_price
 * @property number $discount_price
 * @property number $inc_price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property number $stone_size
 * @property string $setting_type
 * @property string $side_setting_type
 * @property integer $min_size_id
 * @property integer $max_size_id
 * @property string $carat_weight
 * @property string $average_ss_colour
 * @property string $average_ss_clarity
 * @property integer $approx_stones
 * @property number $band_width
 * @property int $is_sold_out
 * @property int $in_store
 * @property int $is_top
 * @property bool $is_active
 * @property int $custom_sort
 * @property bool $has_new_renders
 *
 * @property mixed $old_calculated_price
 * @property mixed $calculated_price
 * @property string $h1
 * @property string $h2
 * @property string $title
 * @property string $subTitle
 * @property mixed $delivery_period
 * @property int $delivery_period_days
 * @property string $text_for_center_stone
 * @property boolean $is_include_center_stone
 *
 * @property mixed $size_slug extra magic
 */
class Product extends Model implements HasMedia, IPromocode, ProductStates
{
    use ProductCompositions;
    use HasTranslations;
    use Likeable;
    use InteractsWithMedia;
    use PriceRateCalculation;
    use PivotEventTrait;
    use Searchable;

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
    //public $custom_fields_collection = [];
    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
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
        'style_id',
        'shape_id',
        'brand_id',
        'min_size_id',
        'max_size_id',
        'metal_id',
        'carat_weight',
        'average_ss_colour',
        'average_ss_clarity',
        'approx_stones',
        'is_sold_out',
        'delivery_period',
        'delivery_period_days',
        'in_store',
        'text_for_center_stone',
        'is_include_center_stone',
        'is_active',
        'custom_sort',
        'has_new_renders',
        'gender'
    ];

    protected $hidden = [
        'category_id',
        'style_id',
        'shape_id',
        'brand_id',
        'min_size_id',
        'max_size_id',
        'metal_id',
    ];

    protected $estimateDeliveryTime = [];

    protected static function boot()
    {
        parent::boot();

        $pivotHandler = function ($model, $relationName) {
            if ($relationName == 'offers') {
                RecalculateDiscountPricesJob::dispatch($model);
            }
        };

        static::pivotAttached($pivotHandler);
        static::pivotUpdated($pivotHandler);
        static::pivotDetached($pivotHandler);
    }

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->item_name . ($this->stoneShape ? ' ' . $this->stoneShape->title : '');
    }

    public function getH1Attribute()
    {
        return $this->brand->title . ($this->style ? ' ' . $this->style->title : '') . ' ' . $this->category->name;
    }

    public function getH2Attribute()
    {
        return ($this->sub_header) ?: $this->defaultDescription();
    }

    public function defaultDescription()
    {
        return "in ".$this->metal->title . ($this->stoneShape ? ' with ' . $this->stoneShape->title.  ' Center Stone' : '');
    }

    public function getSubTitleAttribute()
    {
        return ($this->style ? $this->style->title . ', ' : '') . $this->metal->title;
    }

    public function findOffers()
    {
        return $this->offers->map(function($offer) {
            return [
                'id'     => $offer->id,
                'title'  => $offer->title,
                'slug'   => $offer->slug,
            ];
        })->toArray();
    }

    /**
     * @param Media|null $media
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
                    ->performOnCollections('img-product')
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
            ->performOnCollections('product-images')
            ->nonQueued();

        $this->addMediaConversion('medium-size')
            ->width(650)
            ->height(650)
            ->performOnCollections('product-images')
            ->nonQueued();

        $this->addMediaConversion('feed')
            ->width(250)
            ->height(250)
            ->performOnCollections('product-images')
            ->nonQueued();

    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('img-product')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('product-images')
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('product-video')
            ->singleFile()
            ->useDisk(config('filesystems.cloud'));

        $this
            ->addMediaCollection('product-images-3d')
            ->useDisk(config('filesystems.cloud'));
    }

    public function searchableAs()
    {
        return 'products';
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->h1 . ' ' . $this->h2,
            'sku'   => $this->sku
        ];
    }

    public function getUri()
    {
        return Str::lower(str_replace(' ', '-', $this->h1) . '_' . $this->id . '_c');
    }

    public function cachedImages()
    {
        try {
            return cache()->remember('product_images' . $this->id, 24 * 60, function() {
                $images = $this->getMedia('product-images');
                return [
                    'preview' => $images->count() > 0 ? (new ImageResource($images->first()))->resolve() : null,
                    'images' => $images->count() > 0 ? ImageResource::collection($images)->resolve() : null,
                ];
            });
        } catch (\Exception $e) {
            logger($e);
        }

        return [];
    }

    public function flushImageCache()
    {
        try {
            cache()->delete('product_images' . $this->id);
        } catch (\Exception $e) {
            logger($e);
        }
    }

    public function getSoldOutTitle()
    {
        return $this->is_sold_out ? trans('api.sold-out') : '';
    }

    public function scopeBrand(Builder $builder, $brandId = null)
    {
        if ($brandId) {
            $builder->where('brand_id', $brandId);
        }
    }

    public function scopeCategory(Builder $builder, $categoryId = null)
    {
        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getCollectionId()
    {
        return $this->brand_id;
    }

    public function getCategorySlug()
    {
        return $this->category->slug;
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
        return !$this->is_sold_out && $this->id;
    }

    public function previewImageUrl()
    {
        return $this->getFirstMediaUrl(
            'img-product',
            CommonHelper::nameMediaConversion(
                $this->formats[1],
                $this->feedConversions[0][0],
                $this->feedConversions[0][1]
            )
        ) ?: '#';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function minSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maxSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

}
