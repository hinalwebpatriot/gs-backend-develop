<?php

namespace lenal\catalog\Helpers;

use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use lenal\catalog\Collections\ProductFeedCollectionAdapter;
use lenal\catalog\Mail\ProductHintMail;
use lenal\catalog\Mail\ShareCompleteRingsMail;
use lenal\catalog\Mail\ShareItemsMail;
use lenal\catalog\Models\CompleteRing;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\FilterDescription;
use lenal\catalog\Models\ProductHint;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\ProductSharingList;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Models\SharedCompleteRings;
use lenal\catalog\Models\SharingProduct;
use lenal\catalog\Resources\CompleteRingResource;
use lenal\catalog\Resources\DiamondResource;
use lenal\catalog\Resources\EngagementRingResource;
use lenal\catalog\Resources\FilterDescriptionCollection;
use lenal\catalog\Resources\ProductResource;
use lenal\catalog\Resources\WeddingRingResource;
use lenal\catalog\Facades\CartHelper;
use lenal\catalog\Facades\FavoritesCompareHelper;
use lenal\catalog\Facades\ConstructorHelper;

class CommonHelper
{
    // helper functions

    public function getProductClassByType($type)
    {
        $classes = [
            'diamonds' => Diamond::class,
            'diamond' => Diamond::class,
            'engagement-rings' => EngagementRing::class,
            'wedding-rings' => WeddingRing::class,
            'products' => Product::class,
        ];

        return isset($classes[$type])? $classes[$type]: null;
    }

    public function getTypesByProductClass($class)
    {
        $classes = [
            Diamond::class => 'diamonds',
            EngagementRing::class => 'engagement-rings',
            WeddingRing::class => 'wedding-rings',
            Product::class => 'products',
        ];

        return isset($classes[$class])? $classes[$class]: null;
    }

    public function getProductResourceByType($type)
    {
        $resources = [
            'diamonds' => DiamondResource::class,
            'engagement-rings' => EngagementRingResource::class,
            'wedding-rings' => WeddingRingResource::class,
            'products' => ProductFeedCollectionAdapter::class,
        ];

        return isset($resources[$type])? $resources[$type]: null;
    }

    public function findSavedItem($id, $type)
    {
        if (!$productClass = $this->getProductClassByType($type)) {
            return null;
        }
        return $item = $productClass::whereId($id)->first();
    }

    public function getSavedItemsFromCookie($cookieKey)
    {
        return json_decode(Cookie::get($cookieKey), true) ? : [];
    }

    // synchronize db with cookie after login

    public function synchronizeSavedItems($user)
    {
        CartHelper::synchronizeCart($user);
        FavoritesCompareHelper::synchronizeCompare($user);
        FavoritesCompareHelper::synchronizeFavorites($user);
    }

    // viewed items
    public function addToViewed ($id, $type) {
        $viewedCookie = $this->getSavedItemsFromCookie('viewed');
        // remove if exists
        $itemRemove = Arr::first(Arr::where($viewedCookie, function ($value) use ($id, $type) {
            return ($value['id'] == $id && $value['type'] == $type);
        }));
        if (($index = array_search($itemRemove,  $viewedCookie)) !== false) {
            unset($viewedCookie[$index]);
        }

		$viewedCookie[] = ['id' => $id, 'created_at' => time(), 'type' => $type];
		if (count($viewedCookie) > 10) {
			array_shift($viewedCookie);
		}
        Cookie::queue(Cookie::forever('viewed', json_encode($viewedCookie)));
    }

    public function getViewedItems($type = null) {
        $viewedCookie = $this->getSavedItemsFromCookie('viewed');

        if (!$type) {
            return $viewedCookie;
        }
        $viewedCookieType = Arr::where($viewedCookie, function ($value) use ($type) {
            $value["type"] == $type ? $val = $value : $val=null;
            return $val;
        });
        return Arr::pluck($viewedCookieType, 'id', 'created_at');
    }

    // user picks (latest viewed, favorites and compares)
    public function getUserPicks($type, $returnItemsData = false) {
        // get viewed, favorites and compares
        $viewed = $this->getViewedItems($type);
        if (($user = Auth::guard('api')->user())) {
            $favorites = FavoritesCompareHelper::getLikesIdByTime(
                $this->getProductClassByType($type), $user->id);
            $compares = FavoritesCompareHelper::getCompareIdByTime(
                $this->getProductClassByType($type), $user->id);
        } else {
            $favorites = Arr::pluck(
                $this->getSavedItemsFromCookie("favorites-$type"),
                'id', 'created_at');
            $compares = Arr::pluck(
                $this->getSavedItemsFromCookie("compare-$type"),
                'id', 'created_at');
        }
        // merge all and sort by timestamp
        $pickHistoryFull = array_map(function ($value) {return intval($value);}, $viewed+$favorites+$compares);
        krsort($pickHistoryFull);
        $pickHistoryId = array_values(array_unique($pickHistoryFull));

        if ($returnItemsData) {
            // in case we need this data
            return $pickHistoryId;
        }

        if (empty($pickHistoryId)) {
            return response()->noContent();
        }
        // return items
        $resource = $this->getProductResourceByType($type);
        $class = $this->getProductClassByType($type);
        return $resource::collection(
            $class
                ::withCalculatedPrice()
                ->whereIn('id', $pickHistoryId)
                ->orderByRaw(DB::raw("FIELD(id, ".implode(',', $pickHistoryId).")"))
                ->take(10)
                ->get()
        );
    }

    // user picks (latest viewed, favorites and compares)
    public function getUserAllPicks($returnItemsData = false)
    {
        $types = ["engagement-rings", "wedding-rings"];
        $collection = [];
        foreach ($types as $type)
        {
            // get viewed, favorites and compares
            $viewed = $this->getViewedItems($type);

            if (($user = Auth::guard('api')->user())) {
                $favorites = FavoritesCompareHelper::getLikesIdByTime(
                    $this->getProductClassByType($type), $user->id);
                $compares = FavoritesCompareHelper::getCompareIdByTime(
                    $this->getProductClassByType($type), $user->id);
            } else {
                $favorites = Arr::pluck(
                    $this->getSavedItemsFromCookie("favorites-$type"),
                    'id', 'created_at');
                $compares = Arr::pluck(
                    $this->getSavedItemsFromCookie("compare-$type"),
                    'id', 'created_at');
            }


            // merge all and sort by timestamp
            $pickHistoryFull = array_map(function ($value) {
                return intval($value);
            }, $viewed + $favorites + $compares);
            krsort($pickHistoryFull);
            $pickHistoryId = array_values(array_unique($pickHistoryFull));

            if ($returnItemsData) {
                // in case we need this data
                return $pickHistoryId;
            }

            if (!empty($pickHistoryId)) {
                //$resource = $this->getProductResourceByType($type);
                $class = $this->getProductClassByType($type);
                $collection[] = $class
                        ::withCalculatedPrice()
                        ->whereIn('id', $pickHistoryId)
                        ->orderByRaw(DB::raw("FIELD(id, ".implode(',', $pickHistoryId).")"))
                        ->take(10)
                        ->get();
            }

        }
        if (count($collection) == 0) {
            return response()->noContent();
        }
        if (count($collection) == 1) {
            $all_rings = $collection[0];
            return ProductResource::collection($all_rings);
        }

        $all_rings = $collection[0]->merge($collection[1]);

        return ProductResource::collection($all_rings);
    }


    public function viewedItems($type)
    {
        $viewed = $this->getViewedItems($type);
        if (empty($viewed)) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }

        krsort($viewed);
        $pickHistoryId = array_values(array_unique($viewed));

        $resource = $this->getProductResourceByType($type);
        $class = $this->getProductClassByType($type);
        return $resource::collection(
            $class
                ::withCalculatedPrice()
                ->whereIn('id', $pickHistoryId)
                ->orderByRaw(DB::raw("FIELD(id, ".implode(',', $pickHistoryId).")"))
                ->take(10)
                ->get()
        );
    }

    public function saveShareList($items)
    {
        $list = ProductSharingList::create();
        $items->each(function ($product) use ($list){
            $shareItem = SharingProduct::create();
            $shareItem->product()->associate($product);
            $shareItem->list()->associate($list);
            $shareItem->save();
        });
        return ['id' => $list->id];
    }

    public function getShareList($id, $grouped = false)
    {
        $list = SharingProduct
            ::with([
                'product' => function ($query) {
                    $query->withCalculatedPrice();
                },
            ])
            ->where('list_id', $id)
            ->get();

        if ($list->isEmpty()) {
            return response()->noContent();
        }

        $items = $list->map(function (SharingProduct $item){
            return $item->product ?: $item->restoreProduct();
        });

        if (!$grouped) {
            return [
                'data' =>
                    [
                        'count' => count($items),
                        'items' => ProductResource::collection($items)
                    ]
            ];
        }
        // grouped by product type
        $items = $items->groupBy(function($item){ return get_class($item);});
        $productTypes = [
            'diamonds',
            'engagement-rings',
            'wedding-rings',
            'products'
        ];
        $shareList = [];
        foreach ($productTypes as $type) {
            $resourceClass = $this->getProductClassByType($type);
            $list = isset($items[$resourceClass])
                ? $items[$resourceClass]
                : collect();
            $shareListData = [
                'data' =>
                    [
                        'count' => count($list),
                        'items' => ProductResource::collection($list)
                    ]
            ];
            $shareList[$type] = Arr::get($shareListData, 'data');
        }
        return  ['data' => $shareList];
    }

    public function clearCookieItems()
    {
        $categories = [
            'diamonds',
            'engagement-rings',
            'wedding-rings',
            'products'
        ];
        foreach ($categories as $category) {
            Cookie::queue(Cookie::forget("favorites-$category"));
            Cookie::queue(Cookie::forget("compare-$category"));
        }
        Cookie::queue(Cookie::forget("cart"));
    }

    public function shareListViaEmail($shareListId, $email, $frontendSharePath, $shareType = 'favorites')
    {
        $shareLink = env('FRONTEND_URL').$frontendSharePath.$shareListId;
        try {
            Mail::to($email)->send(new ShareItemsMail($shareType, $shareLink));
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
        return ['message' => trans('api.favorites.share_email_successfully_sent')];

    }

    public function getSuggestsHomepage()
    {
        // get latest user viewed/favs/compare items data
        $viewedRings = $this->getUserPicks('engagement-rings', true);
        if (empty($viewedRings)) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }
        $viewedRingsData = EngagementRing::withCalculatedPrice()
            ->whereIn('id', $viewedRings)
            ->orderByRaw(DB::raw("FIELD(id, ".implode(',', $viewedRings).")"))
            ->get()
            ->take(10);
        if ($viewedRingsData->isEmpty()) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }

        // select other rings with same collection and price = average collection price +/- 30%
        $averagePrice = $viewedRingsData->avg('calculated_price');
        return EngagementRingResource::collection(
            EngagementRing::withCalculatedPrice()
                ->whereNotIn('id', $viewedRingsData->map(function ($item) { return $item->id; })->toArray())
                ->whereIn('ring_collection_id', $viewedRingsData->map(function ($item) { return $item->ring_collection_id; })->toArray())
                ->inRandomOrder()
                ->get()
                ->filter(function ($item) use ($averagePrice) {
                    return ($item->calculated_price >=$averagePrice * 0.7 && $item->calculated_price <=$averagePrice * 1.3 );
                })
                ->take(10)
        );

    }

    public function sendProductHint($request)
    {
        try {
            ProductHint::create([
                'email' => request('recipient_email'),
                'product_type' => $this->getProductClassByType($request->type),
                'product_id' => $request->id
            ]);
            // send mail
            Mail::to(request('recipient_email'))->send(new ProductHintMail($request->all()));
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
        return response(['message' => trans('api.mail.default.success_send')]);
    }

    public function shareCompleteRings()
    {
        // get complete rings
        $completeRingsData = ($user_id = Auth::guard('api')->id())
            ? CompleteRing::where('user_id', $user_id)->get()->map(function ($completeRing) {
                    return [
                        'diamond_id' => $completeRing->diamond_id,
                        'ring_id' => $completeRing->ring_id,
                        'ring_size' => $completeRing->ring_size,
                    ];
                })
        : ConstructorHelper::getCompleteRingsFromCookie();
        if ($completeRingsData->isEmpty()) {
            return response(
                ['message' => trans('api.complete_share.items_not_found')],
                Response::HTTP_NOT_FOUND
            );
        }
        // save share data
        $savedShare = SharedCompleteRings::create([
            'data' => $completeRingsData->toJson()
        ]);
        return response(['id' => $savedShare->id]);
    }

    public function shareCompleteRingsMail()
    {
        try {
            Mail::to(request('email'))->send(new ShareCompleteRingsMail(env('FRONTEND_URL').request('link')));
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
        return response(['message' => trans('api.mail.default.success_send')]);
    }

    public function shareCompleteRingsList(int $id)
    {
        if (!$list = SharedCompleteRings::where('id', $id)->first()) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }
        $items = collect(json_decode($list->data, true))->map(function ($item) {
            return CompleteRing::make($item);
        });

        return CompleteRingResource::collection($items);
    }

    public function getFilterDescriptions()
    {
        return new FilterDescriptionCollection(
            FilterDescription
                ::all()
                ->groupBy('product_feed')
        );
    }

    public function homepage360Sliders()
    {
        return response([
            'man' => WeddingRingResource::collection(
                WeddingRing::hasMediaFiles('wedding-images-3d')
                    ->withResourceRelation()
                    ->where('gender', 'male')
                    ->inRandomOrder()->take(10)->get()
            ),
            'woman' => WeddingRingResource::collection(
                WeddingRing::hasMediaFiles('wedding-images-3d')
                    ->withResourceRelation()
                    ->where('gender', 'female')
                    ->inRandomOrder()->take(10)->get()
            )
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function fileManagerStorage()
    {
        return Storage::disk(env('FILEMANAGER_DISK', 's3-private'));
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function mediaStorage()
    {
        return Storage::disk(env('MEDIA_STORAGE', 's3'));
    }

    /**
     * Генерирует имя конверсии изображения
     *
     * @param  string  $format
     * @param  int     $width
     * @param  int     $height
     *
     * @return string
     */
    public function nameMediaConversion(string $format, int $width, int $height): string
    {
        return sprintf('%s-%dx%d', $format, $width, $height);
    }

    /**
     * Генерирует имя размера для конверсии изображения
     *
     * @param  int     $width
     * @param  int     $height
     *
     * @return string
     */
    public function sizeMediaConversion(int $width, int $height): string
    {
        return sprintf('%dx%d',$width, $height);
    }
}
