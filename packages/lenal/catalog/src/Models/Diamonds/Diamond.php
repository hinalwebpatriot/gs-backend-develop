<?php

namespace lenal\catalog\Models\Diamonds;

use Carbon\Carbon;
use Conner\Likeable\Likeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use lenal\blocks\Models\StaticBlock;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Compare;
use lenal\catalog\Models\IPromocode;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\ProductStates;
use lenal\catalog\Services\DeliveryTimeService;
use lenal\MarginCalculate\MarginCalculation;
use lenal\PriceCalculate\PriceCalculation;
use lenal\reviews\Models\Review;
use lenal\search\Traits\DiamondSearchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int          $id
 * @property string       $title
 * @property string       $slug
 * @property string       $sku
 * @property string       $description
 * @property string       $calculated_price
 * @property string       $old_calculated_price
 * @property mixed        $carat
 * @property int          $shape_id
 * @property string       $video
 * @property int          $is_offline
 * @property int          $in_store
 * @property int          $enabled
 * @property int          $delivery_period
 * @property int          $delivery_period_days
 * @property string       $type
 *
 * @property Carbon       $updated_at
 * @property Color        $color
 * @property Shape        $shape
 * @property Manufacturer $manufacturer
 */
class Diamond extends Model implements HasMedia, IPromocode, ProductStates
{
    use Likeable;
    use PriceCalculation;
    use MarginCalculation;
    use InteractsWithMedia;
    use Searchable, DiamondSearchable {
        DiamondSearchable::searchableAs insteadof Searchable;
        DiamondSearchable::toSearchableArray insteadof Searchable;
    }

    public string $cacheDeliveryKey = 'diamond:delivery:%s';

    protected $fillable = [
        'created_at',
        'updated_at',
        'enabled',
        'slug',
        'sku',
        'carat',
        'table',
        'depth',
        'raw_price',
        'margin_price',
        'stock_number',
        'certificate_number',
        'certificate',
        'size_ratio',
        'length',
        'width',
        'height',
        'girdle',
        'manufacturer_id',
        'shape_id',
        'color_id',
        'clarity_id',
        'cut_id',
        'polish_id',
        'symmetry_id',
        'fluorescence_id',
        'culet_id',
        'video',
        'is_offline',
        'in_store',
        'delivery_period',
        'delivery_period_days',
        'type'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'manufacturer_id',
        'shape_id',
        'color_id',
        'clarity_id',
        'cut_id',
        'polish_id',
        'symmetry_id',
        'fluorescence_id',
        'culet_id',
    ];

    protected $estimateDeliveryTime = [];

    public function getTitleAttribute(): string
    {
        return
            number_format($this->carat, 2, '.', '').' '.
            trans('api.catalog.carat').' '.
            ($this->shape->title ?? '').' '.
            trans('api.catalog.shape');
    }

    public function getSubtitleAttribute(): string
    {
        return
            trans('api.catalog.diamond').' '.
            '- '.
            trans('api.catalog.certificate');
    }

    public function shape()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Shape');
    }

    public function clarity()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Clarity');
    }

    public function color()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Color');
    }

    public function cut()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Cut');
    }

    public function symmetry()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Symmetry');
    }

    public function polish()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Polish');
    }

    public function fluorescence()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Fluorescence');
    }

    public function culet()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Culet');
    }

    public function manufacturer()
    {
        return $this->belongsTo('lenal\catalog\Models\Diamonds\Manufacturer');
    }

    /**
     * @param  Media|null  $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(130)
            ->height(130);

        $this->addMediaConversion('medium-size')
            ->width(800)
            ->height(600);

        $this->addMediaConversion('feed')
            ->width(115)
            ->height(115);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('diamond-images')
            ->useDisk(config('filesystems.cloud'));
    }

    public function staticBlocks()
    {
        return $this->belongsToMany(StaticBlock::class, 'static_blocks_diamonds');
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
     * @param  Builder  $query
     */
    public function scopeWithResourceRelation(Builder $query)
    {
        $query->with([
            'shape',
            'clarity',
            'color',
            'cut',
            'symmetry',
            'polish',
            'fluorescence',
            'culet',
        ]);
    }

    public function createSlug()
    {
        if (!$this->slug && $this->stock_number) {
            $this->slug = $this->stock_number;
        }
    }

    public function createSku()
    {
        if (!$this->sku) {
            $this->sku = $this->stock_number;
        }
    }

    public function defaultImageUrl()
    {
        $storage = Storage::disk(config('filesystems.cloud'));
        $color = $this->color ? $this->color->slug : "H";
        $shape = $this->shape ? $this->shape->slug : null;

        if (!$storage || !$shape) {
            return '';
        }

        return $storage->url('shapes'.'/'.Str::title($shape).'/'.Str::title($color).'.png');
    }

    public function previewImageUrl()
    {
        $images = $this->getMedia('diamond-images');

        return $images->count() > 0 ? $images->first()->getFullUrl('feed') : $this->defaultImageUrl();
    }

    /**
     * @return array
     */
    public function defaultImage()
    {
        $storage = Storage::disk(config('filesystems.cloud'));
        $color = $this->color ? $this->color->slug : 'H';

        $base = 'shapes'.'/'.Str::title($this->shape->slug).'/';
        $basename = Str::title($color).'.png';

        $originUrl = $storage->url($base.$basename);
        $mimeType = 'image/jpeg';

        if (strrchr($basename, '.') == '.png') {
            $mimeType = 'image/png';
        }

        return [
            'name'      => substr($basename, 0, -4),
            'mime_type' => $mimeType,
            'size'      => 6200,
            'path'      => [
                'origin' => $originUrl,
                'thumb'  => $originUrl,
                'medium' => $originUrl,
                'feed'   => $storage->url($base.'feed__'.$basename),
            ],
        ];
    }

    public function videoNormalize()
    {
        if (
            $this->video && strpos($this->video, 'diamond-videos') !== false &&
            !Str::startsWith($this->video, 'http')
        ) {
            $this->video = Storage::disk(config('filesystems.cloud'))->url($this->video);
        }
    }

    public function getCategorySlug()
    {
        return Category::DIAMONDS;
    }

    public function getEstimateDeliveryTime()
    {
        if (!$this->estimateDeliveryTime) {
            if (!Cache::has($this->getDeliveryKeyCache())) {
                Cache::put($this->getDeliveryKeyCache(), app(DeliveryTimeService::class)
                    ->getDeliveryTime(
                        $this->delivery_period,
                        $this->delivery_period_days,
                        $this->getCategorySlug(),
                        null,
                        $this->sku
                    ));
            }
            $this->estimateDeliveryTime = Cache::get($this->getDeliveryKeyCache());
        }

        return $this->estimateDeliveryTime;
    }

    public function isAvailable()
    {
        return $this->enabled == 1 && $this->id;
    }

    public function getUri()
    {
        return 'diamonds/product/'.$this->slug.'_'.$this->id;
    }

    public function getDeliveryKeyCache(): string
    {
        return sprintf($this->cacheDeliveryKey, $this->id);
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getCollectionId()
    {
        return null;
    }
}
