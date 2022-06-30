<?php

namespace lenal\sitemap\Jobs;

use Carbon\Carbon;
use GSD\Containers\Prerender\Jobs\PushPagesFromSitemapJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use lenal\blog\Models\Article;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\reviews\Models\Review;
use lenal\seo\Models\Meta;
use lenal\static_pages\Models\StaticPage;
use Watson\Sitemap\Facades\Sitemap;


class SitemapGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MAIN_SITEMAP = 'sitemap/sitemap.xml';
    const STATIC_SITEMAP = 'sitemap/sitemaps/static.xml';
    const STATIC_PREFIX = '/';
    const STATIC_ARTICLE = '/blog/';

    const ENGAGEMENT_SITEMAP = 'sitemap/sitemaps/engagement.xml';
    const WEDDING_SITEMAP = 'sitemap/sitemaps/wedding.xml';
    const PRODUCT_SITEMAP = 'sitemap/sitemaps/products.xml';
    const REVIEWS_SITEMAP = 'sitemap/sitemaps/reviews.xml';

    const ENGAGEMENT_PREFIX = '/engagement-rings/product/';
    const WEDDING_PREFIX = '/wedding-rings/product/';

    /**
     * @var Filesystem
     */
    private $storage;

    protected static $category_path = [
        "/diamonds/",
        "/engagement-rings/",
        "/engagement-rings/asscher",
        "/engagement-rings/round",
        "/engagement-rings/princess",
        "/engagement-rings/heart",
        "/engagement-rings/pear",
        "/engagement-rings/marquise",
        "/engagement-rings/cushion",
        "/engagement-rings/emerald",
        "/engagement-rings/radiant",
        "/engagement-rings/oval",
        "/engagement-rings/solitaire",
        "/engagement-rings/three-stones",
        "/engagement-rings/side-stones",
        "/engagement-rings/vintage",
        "/engagement-rings/yellow-gold",
        "/engagement-rings/rose-gold",
        "/engagement-rings/halo",
        "/engagement-rings/platinum",
        "/engagement-rings/white-gold",
        "/wedding-rings/diamonds",
        "/wedding-rings/plain",
        "/wedding-rings/rose-gold",
        "/wedding-rings/yellow-gold",
        "/wedding-rings/white-gold",
        "/wedding-rings/platinum",
        "/wedding-rings/",
        "/wedding-rings/mens",
        "/wedding-rings/womens",
        "/contact-us",
        "/faq",
        "/blog/"
    ];

    /**
     * @var Carbon[]
     */
    private $sitemapLastModes = [
        'engagement' => null,
        'wedding' => null,
        'product' => null,
        'review' => null,
        'static' => null,
        'diamond' => null,
    ];

    /**
     * @var Carbon[]
     */
    private $categoriesLastModes = [];

    private $engagementLastMode;
    private $weddingLastMode;
    private $productLastMode;
    private $reviewLastMode;

    public function generateSitemap()
    {
        Sitemap::addSitemap($this->storage->url($this->getStaticSitemap()), Carbon::now());
        Sitemap::addSitemap($this->storage->url($this->getEngagementSitemap()), $this->sitemapLastModes['engagement']);
        Sitemap::addSitemap($this->storage->url($this->getWeddingSitemap()), $this->sitemapLastModes['wedding']);
        Sitemap::addSitemap($this->storage->url($this->getProductSitemap()), $this->sitemapLastModes['product']);
        Sitemap::addSitemap($this->storage->url($this->getReviewsSitemap()), $this->sitemapLastModes['review']);

        $this->storage->put($this->getMainSitemap(),  (string)Sitemap::xmlIndex()->__toString());

        PushPagesFromSitemapJob::dispatch([
            $this->storage->url($this->getStaticSitemap())
        ]);

        return Sitemap::clear();
    }

    protected function changeSitemapLastMod($sitemapPage, ?Carbon $date)
    {
        if ($date && (is_null($this->sitemapLastModes[$sitemapPage]) || $date->isAfter($this->sitemapLastModes[$sitemapPage]))) {
            $this->sitemapLastModes[$sitemapPage] = $date;
        }
    }

    protected function changeCategoryLastMod($category, $option, $optionValue, ?Carbon $date)
    {
        $url = '/' . $category . '/';
        $optionValue = str_replace(['18ct', '9ct'], '', $optionValue);
        $optionValue = str_replace(' ', '-', trim($optionValue));
        if ($option == 'gender') {
            $url .= ($optionValue == 'male' ? 'mens' : 'womens');
        } elseif ($optionValue) {
            $url .= $optionValue;
        }

        $currentDate = $this->categoriesLastModes[$url] ?? null;

        if (($optionValue || !$option) && $date && (is_null($currentDate) || $date->isAfter($currentDate))) {
            $this->categoriesLastModes[$url] = $date;
        }
    }

    public function staticPageSitemap()
    {
        $main_page = $this->storage->url("/");
        Sitemap::addTag($main_page, new \DateTime(), 'weekly', 1); // main page

        $meta = Meta::query()->get();
        foreach ($meta as $item) {
            if ($item->sitemap_page_url) {
                Sitemap::addTag(
                    $this->storage->url($item->sitemap_page_url),
                    $item->updated_at,
                    'weekly',
                    0.9
                );
                $this->changeSitemapLastMod('static', $item->updated_at);
            }
        }

        $articles = Article::all()->toArray();
        $pages = StaticPage::all()->toArray();

        $excludes = ['cookies', 'test'];
        foreach ($pages as $page) {
            if (!in_array($page['code'], $excludes)) {
                if (!Meta::query()->where('page', Str::lower($page["code"]))->exists()) {
                    $path = $this->storage->url(self::STATIC_PREFIX.Str::lower($page["code"]));
                    Sitemap::addTag($path, $page['updated_at'], 'monthly', 0.5);
                }
            }
        }

        foreach ($articles as $article) {
            if (!Meta::query()->where('page', 'blog-'.Str::lower($article["slug"]))->exists()) {
                $path = $this->storage->url(self::STATIC_ARTICLE.Str::lower($article["slug"]));
                Sitemap::addTag($path, $article['updated_at'], 'weekly', 0.7);

                if ($article['updated_at']) {
                    $this->changeCategoryLastMod('blog', null, null,
                        Carbon::createFromTimestamp($article['updated_at']));
                }
            }
        }

        $this->storage->put($this->getStaticSitemap(), (string) Sitemap::xml());

        return Sitemap::clear();
    }

    public function engagementSitemap()
    {
        /** @var EngagementRing[] $rings */
        $rings = EngagementRing::with(['ringCollection', 'metal', 'ringStyle', 'stoneShape'])
            ->where('is_active', true)
            ->get();

        foreach ($rings as $ring) {
            $path = $this->storage->url("/" . $ring->getUri());
            Sitemap::addTag($path, $ring->updated_at, 'weekly', 0.4);

            $this->changeSitemapLastMod('engagement', $ring->updated_at);
            $this->changeCategoryLastMod('engagement-rings', 'metal', $ring->metal->title ?? null, $ring->updated_at);
            $this->changeCategoryLastMod('engagement-rings', 'style', $ring->ringStyle->title ?? null, $ring->updated_at);
            $this->changeCategoryLastMod('engagement-rings', 'shape', $ring->stoneShape->title ?? null, $ring->updated_at);
        }

        $this->changeCategoryLastMod('engagement-rings', null, null, $this->sitemapLastModes['engagement']);

        $this->storage->put($this->getEngagementSitemap(),  (string)Sitemap::xml());
        return Sitemap::clear();
    }

    public function weddingSitemap()
    {
        /** @var WeddingRing[] $weddings */
        $weddings = WeddingRing::with(['ringCollection', 'metal', 'ringStyle'])
            ->where('is_active', true)
            ->get();

        foreach ($weddings as $wedding) {
            $path = $this->storage->url("/". $wedding->getUri());
            Sitemap::addTag($path, $wedding->updated_at, 'weekly', 0.4);

            $this->changeSitemapLastMod('wedding', $wedding->updated_at);

            $this->changeCategoryLastMod('wedding-rings', 'metal', $wedding->metal->title ?? null, $wedding->updated_at);
            $this->changeCategoryLastMod('wedding-rings', 'style', $wedding->ringStyle->title ?? null, $wedding->updated_at);
            $this->changeCategoryLastMod('wedding-rings', 'gender', $wedding->gender, $wedding->updated_at);
        }

        $this->changeCategoryLastMod('wedding-rings', null, null, $this->sitemapLastModes['wedding']);

        $this->storage->put($this->getWeddingSitemap(),  (string)Sitemap::xml());
        return Sitemap::clear();
    }

    public function productsSitemap()
    {
        /** @var Product[]|Collection $products */
        $products = Product::with(['brand', 'style', 'category'])->where('is_active', true)->get();

        $catLastModified = [];

        foreach ($products as $product) {
            $path = $this->storage->url('/' . $product->getUri());
            Sitemap::addTag($path, $product->updated_at, 'weekly', 0.4);

            if ($product->updated_at && (!isset($catLastModified[$product->category->slug]) || $product->updated_at->getTimestamp() > $catLastModified[$product->category->slug])) {
                $catLastModified[$product->category->slug] = $product->updated_at->getTimestamp();
            }

            $this->changeSitemapLastMod('product', $product->updated_at);
        }

        $this->storage->put($this->getProductSitemap(),  (string) Sitemap::xml());

        return Sitemap::clear();
    }

    public function reviewsSitemap()
    {
        /** @var Review[] $reviews */
        $reviews = Review::with(['product'])->where('is_active', 1)->get();

        $lastDateUpdate = 0;

        foreach ($reviews as $review) {
            if ($review->product) {
                if (!Meta::query()->where('page', 'reviews-'.Str::lower($review->product->getUri()))->exists()) {
                    $path = $this->storage->url('/reviews/'.$review->product->getUri());
                    Sitemap::addTag($path, $review->updated_at, 'monthly', 0.3);

                    if ($review->updated_at->getTimestamp() > $lastDateUpdate) {
                        $lastDateUpdate = $review->updated_at->getTimestamp();
                    }

                    $this->changeSitemapLastMod('review', $review->updated_at);
                }
            }
        }

        Sitemap::addTag($this->storage->url("/reviews/"), $this->lastmodDate($lastDateUpdate), false, false);

        $this->storage->put($this->getReviewsSitemap(),  (string)Sitemap::xml());
        return Sitemap::clear();
    }

    private function lastmodDate($timestamp)
    {
        if (!$timestamp) {
            return false;
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * @return string
     */
    public function getMainSitemap()
    {
        return self::MAIN_SITEMAP;
    }

    /**
     * @return string
     */
    public function getStaticSitemap()
    {
        return self::STATIC_SITEMAP;
    }


    /**
     * @return string
     */
    public function getEngagementSitemap()
    {
        return self::ENGAGEMENT_SITEMAP;
    }

    /**
     * @return string
     */
    public function getWeddingSitemap()
    {
        return self::WEDDING_SITEMAP;
    }

    public function getProductSitemap()
    {
        return self::PRODUCT_SITEMAP;
    }


    public function getReviewsSitemap()
    {
        return self::REVIEWS_SITEMAP;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->storage = Storage::disk(config('filesystems.sitemap'));

        $this->weddingSitemap();
        $this->engagementSitemap();
        $this->productsSitemap();
        $this->reviewsSitemap();
        $this->staticPageSitemap();

        $this->generateSitemap();
    }
}
