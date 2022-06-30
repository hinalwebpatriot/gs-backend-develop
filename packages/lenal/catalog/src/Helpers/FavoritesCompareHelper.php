<?php

namespace lenal\catalog\Helpers;

use Conner\Likeable\Like;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use lenal\catalog\Mail\ShareItemsMail;
use lenal\catalog\Models\Compare;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\ProductSharingList;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Facades\CommonHelper as CommonHelper;
use lenal\catalog\Models\SharingProduct;


class FavoritesCompareHelper
{
    // favorites

    public function addToFavorites($ids, $type)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $itemsFailed = [];
        $itemsAdd = [];
        // check if all items exist
        foreach ($ids as $id) {
            if(!$item = CommonHelper::findSavedItem($id, $type)) {
                $itemsFailed[] = $id;
            }
            $itemsAdd[] = $item;
        }
        if (!empty($itemsFailed)) {
            return response(
                ['message' => trans('api.favorites.error.invalid_request_params'), 'id' => $itemsFailed],
                Response::HTTP_NOT_FOUND);
        }

        // get current favs
        $user = Auth::guard('api')->user();
        $favorites = $user
            ? $this->getFavoriteItems(get_class($item), $user->id)
            : CommonHelper::getSavedItemsFromCookie("favorites-$type");
        $countAvailable = 6 - count($favorites);

        // user authorized - work with db favs
        if ($user) {
            Auth::login($user);
            foreach ($itemsAdd as $item) {
                if (!$favorites->contains($item)) {
                    // add if not maximum
                    if ( $countAvailable-- <= 0) {
                        $itemsFailed[] = $item->id;
                        continue;
                    }
                    $item->like();
                }

            }
            if (!empty($itemsFailed)) {
                return response(
                    ['message' => trans('api.favorites.error.max_items_not_added'), 'id' => $itemsFailed],
                    Response::HTTP_NOT_FOUND);
            }
            return response(['message' => trans('api.favorites.added_ok')]);
        }

        // user is not authorized - work with cookie favs

        foreach ($itemsAdd as $item) {
            $id = $item->id;
            if (empty(Arr::where($favorites, function ($value) use ($id) { return $value['id'] == $id; }))) {
                if ( $countAvailable-- <= 0) {
                    $itemsFailed[] = $item->id;
                    continue;
                }
                $favorites[] = ['id' => $id, 'created_at' => time()];
            }
        }
        if (!empty($itemsFailed)) {
            return response(
                ['message' => trans('api.favorites.error.max_items_not_added'), 'id' => $itemsFailed],
                Response::HTTP_NOT_FOUND)->withCookie(
                cookie()->forever("favorites-$type", json_encode($favorites))
            );
        }
        return response(['message' => trans('api.favorites.added_ok')])->withCookie(
            cookie()->forever("favorites-$type", json_encode($favorites))
        );
    }

    public function getFavoriteItems($type, $userId)
    {
        return Like
            ::with([
                'likeable' => function ($query) {
                    $query->withCalculatedPrice();
                }
            ])
            ->has('likeable')
            ->where('user_id', $userId)
            ->where('likeable_type', $type)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($like) {
                return $like->likeable;
            });
    }

    public function getLikesIdByTime($type, $userId)
    {
        // returns [timestamp => id] (for your-picks block)
        $favorites = Like::with('likeable')
            ->has('likeable')
            ->where('user_id', $userId)
            ->where('likeable_type', $type)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($like) {
                return ['id' => intval($like->likeable_id), 'created_at' => $like->created_at->timestamp];
            })
            ->toArray();
        return Arr::pluck($favorites, 'id', 'created_at');
    }

    public function getUserLikes($userId)
    {
        return Like::with('likeable')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($like) {
                return $like->likeable;
            })
            ->filter(function ($item) {
                return !!$item;
            });
    }

    public function favorites($type = null)
    {
        if ($type) {
            if (!CommonHelper::getProductClassByType($type)) {
                return response(
                    ['message' => trans('api.favorites.error.invalid_request_params')],
                    Response::HTTP_NOT_FOUND);
            }
            return response($this->favoritesListType($type));
        }
        $favoritesCategories = [
            'diamonds',
            'engagement-rings',
            'wedding-rings',
            'products'
        ];
        foreach ($favoritesCategories as $category) {
            $list[$category] = Arr::get($this->favoritesListType($category), 'data');
        }
        return  ['data' => $list];
    }

    public function favoritesListType($type)
    {
        $productClass = CommonHelper::getProductClassByType($type);
        $list = ($user = Auth::guard('api')->user())
            ? $this->getFavoriteItems($productClass, $user->id)
            : $this->getFavoriteItemsFromCookie($type);

        $resourceClass = CommonHelper::getProductResourceByType($type);
        return [
            'data' =>
                [
                    'count' => count($list),
                    'items' => $resourceClass::collection($list)
                ]
        ];
    }

    private function getFavoriteItemsFromCookie($type)
    {
        $itemsCookie = CommonHelper::getSavedItemsFromCookie("favorites-$type");
        $items = array_map(function($value) { return $value['id']; }, $itemsCookie);
        if (empty($items)) {
            return collect();
        }
        $productClass = CommonHelper::getProductClassByType($type);
        return $productClass
            ::withCalculatedPrice()
            ->whereIn('id', $items)
            ->orderByRaw(DB::raw("FIELD(id, ".implode(',', array_reverse($items)).")"))
            ->get();
    }

    private function getFavoriteItemsFromCookieAll()
    {
        $items = collect();
        $items = $items->merge($this->getFavoriteItemsFromCookie('diamonds'));
        $items = $items->merge($this->getFavoriteItemsFromCookie('engagement-rings'));
        $items = $items->merge($this->getFavoriteItemsFromCookie('wedding-rings'));
        $items = $items->merge($this->getFavoriteItemsFromCookie('products'));
        return $items;

    }

    public function removeFromFavorites($id, $type)
    {
        if(!$item = CommonHelper::findSavedItem($id, $type)) {
            return response(
                ['message' => trans('api.compares.error.invalid_request_params')],
                Response::HTTP_NOT_FOUND);
        }
        if ($user = Auth::guard('api')->user()) {
            Auth::login($user);
            $item->unlike();
            return response(['message' => trans('api.favorites.removed_ok')]);
        }
        // non-auth -> cookie
        $favorites = CommonHelper::getSavedItemsFromCookie("favorites-$type");
        $itemRemove = Arr::first(Arr::where($favorites, function ($value) use ($id) { return $value['id'] == $id; }));
        if (($index = array_search($itemRemove,  $favorites)) !== false) {
            unset($favorites[$index]);
        }
        return response(['message' => trans('api.favorites.removed_ok')])->withCookie(
            cookie()->forever("favorites-$type", json_encode($favorites))
        );
    }

    public function removeAllFavorites($type=null)
    {
        $user = Auth::guard('api')->user();
        if ($type) {
            // remove by type
            $user
                ? Like::where(['user_id' => $user->id, 'likeable_type' => CommonHelper::getProductClassByType($type)])
                ->delete()
                : Cookie::queue(Cookie::forget("favorites-$type"));
            return response(['message' => trans('api.favorites.removed_ok')]);
        }
        // remove all
        if($user) {
            Like::where(['user_id' => $user->id])->delete();
        } else {
            $compareCategories = [
                'diamonds',
                'engagement-rings',
                'wedding-rings',
                'products'
            ];
            foreach ($compareCategories as $category) {
                Cookie::queue(Cookie::forget("favorites-$category"));
            }
        }
        return response(['message' => trans('api.favorites.removed_ok')]);
    }

    public function inFavorites($id, $class)
    {
        $user = Auth::guard('api')->user();
        $favorite = $user
            ? Like::where(['user_id' => $user->id, 'likeable_type' => $class, 'likeable_id' => $id])
                ->exists()
            : array_search(
                    $id,
                    CommonHelper::getSavedItemsFromCookie("favorites-".CommonHelper::getTypesByProductClass($class))
                );
        return ($favorite !== false && $favorite !== null);
    }

    // compares

    public function addToCompare($ids, $type)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $itemsFailed = [];
        $itemsAdd = [];
        // check if all items exist
        foreach ($ids as $id) {
            if(!$item = CommonHelper::findSavedItem($id, $type)) {
                $itemsFailed[] = $id;
            }
            $itemsAdd[] = $item;
        }
        if (!empty($itemsFailed)) {
            return response(
                ['message' => trans('api.compares.error.invalid_request_params'), 'id' => $itemsFailed],
                Response::HTTP_NOT_FOUND);
        }
        $user = Auth::guard('api')->user();
        $compareItems = $user
            ? $this->getCompareItems(get_class($item), $user->id)
            : CommonHelper::getSavedItemsFromCookie("compare-$type");
        $countAvailable = 6 - count($compareItems);

        if ($user) {
            // db
            foreach ($itemsAdd as $item) {
                if (!$compareItems->contains($item)) {
                    // add if not maximum
                    if ( $countAvailable-- <= 0) {
                        $itemsFailed[] = $item->id;
                        continue;
                    }
                    $this->addItemToUserCompare($item, $user);
                }

            }
            if (!empty($itemsFailed)) {
                return response(
                    ['message' => trans('api.favorites.error.max_items_not_added'), 'id' => $itemsFailed],
                    Response::HTTP_NOT_FOUND);
            }
            return response(['message' => trans('api.compares.added_ok')]);
        }
        // cookie
        foreach ($itemsAdd as $item) {
            $id = $item->id;
            if (empty(Arr::where($compareItems, function ($value) use ($id) { return $value['id'] == $id; }))) {
                if ( $countAvailable-- <= 0) {
                    $itemsFailed[] = $item->id;
                    continue;
                }
                $compareItems[] = ['id' => $id, 'created_at' => time()];
            }
        }
        if (!empty($itemsFailed)) {
            return response(
                ['message' => trans('api.compares.error.max_items_not_added'), 'id' => $itemsFailed],
                Response::HTTP_NOT_FOUND)->withCookie(
                cookie()->forever("compare-$type", json_encode($compareItems))
            );
        }
        return response(['message' => trans('api.compares.added_ok')])->withCookie(
            cookie()->forever("compare-$type", json_encode($compareItems))
        );
    }

    private function addItemToUserCompare($item, $user)
    {
        $compareItem = new Compare();
        $compareItem->user()->associate($user);
        $item->compares()->save($compareItem);
    }

    public function comparesList($type=null)
    {
        if ($type) {
            if (!CommonHelper::getProductClassByType($type)) {
                return response(
                    ['message' => trans('api.compares.error.invalid_request_params')],
                    Response::HTTP_NOT_FOUND);
            }
            return response($this->comparesListType($type));
        }
        $compareCategories = [
            'diamonds',
            'engagement-rings',
            'wedding-rings',
            'products',
        ];
        foreach ($compareCategories as $category) {
            $list[$category] = Arr::get($this->comparesListType($category), 'data');
        }
        return  ['data' => $list];
    }

    public function comparesListType($type)
    {
        $productClass = CommonHelper::getProductClassByType($type);
        $list = ($user = Auth::guard('api')->user())
            ? $this->getCompareItems($productClass, $user->id)
            : $this->getCompareItemsFromCookie($type, $productClass);

        $resourceClass = CommonHelper::getProductResourceByType($type);
        return [
            'data' =>
                [
                    'count' => count($list),
                    'items' => $resourceClass::collection($list)
                ]
        ];
    }

    public function getCompareItems($type, $userId)
    {
        return Compare::with([
                'product' => function ($query) {
                    $query->withCalculatedPrice();
                },
            ])
            ->has('product')
            ->where('user_id', $userId)
            ->where('product_type', $type)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($compare) {
                return $compare->product;
            });
    }

    public function getCompareIdByTime($type, $userId)
    {
        $compare = Compare::with('product')
            ->where('user_id', $userId)
            ->where('product_type', $type)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return ['id' => $item->product_id, 'created_at' => $item->created_at->timestamp];
            })
            ->toArray();
        return Arr::pluck($compare, 'id', 'created_at');
    }

    private function getCompareItemsAll($userId)
    {
        return Compare::with('product')
            ->has('product')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($compare) {
                return $compare->product;
            });
    }

    private function getCompareItemsFromCookie($type, $productClass)
    {
        $itemsCookie = CommonHelper::getSavedItemsFromCookie("compare-$type");
        $items = array_map(function($value) { return $value['id']; }, $itemsCookie);
        if (empty($items)) {
            return collect();
        }
        return $productClass
            ::withCalculatedPrice()
            ->whereIn('id', $items)
            ->orderByRaw(DB::raw("FIELD(id, ".implode(',', array_reverse($items)).")"))
            ->get();
    }

    private function getCompareItemsFromCookieAll()
    {
        $items = collect();
        $items = $items->merge($this->getCompareItemsFromCookie('diamonds', Diamond::class));
        $items = $items->merge($this->getCompareItemsFromCookie('engagement-rings', EngagementRing::class));
        $items = $items->merge($this->getCompareItemsFromCookie('wedding-rings', WeddingRing::class));
        $items = $items->merge($this->getCompareItemsFromCookie('products', Product::class));
        return $items;

    }

    public function removeFromCompare($id, $type)
    {
        if(!$item = CommonHelper::findSavedItem($id, $type)) {
            return response(
                ['message' => trans('api.compares.error.invalid_request_params')],
                Response::HTTP_NOT_FOUND);
        }

        if ($user = Auth::guard('api')->user()) {
            Compare
                ::whereHas('user', function ($userQuery) use ($user) { $userQuery->where('id', $user->id);})
                ->where(['product_id'=>$id, 'product_type'=>get_class($item)])
                ->delete();
            return response(['message' => trans('api.compares.removed_ok')]);
        }
        // non-auth -> cookie
        $compareItems = CommonHelper::getSavedItemsFromCookie("compare-$type");
        $itemRemove = Arr::first(Arr::where($compareItems, function ($value) use ($id) { return $value['id'] == $id; }));
        if (($index = array_search($itemRemove,  $compareItems)) !== false) {
            unset($compareItems[$index]);
        }

        return response(['message' => trans('api.compares.removed_ok')])->withCookie(
            cookie()->forever("compare-$type", json_encode($compareItems))
        );

    }

    public function removeAllCompares($type=null)
    {
        $user = Auth::guard('api')->user();
        if ($type) {
            // remove by type
            $user
                ? Compare::where(['user_id' => $user->id, 'product_type' => CommonHelper::getProductClassByType($type)])
                    ->delete()
                : Cookie::queue(Cookie::forget("compare-$type"));
            return response(['message' => trans('api.compares.removed_ok')]);
        }
        // remove all
        if($user) {
            Compare::where(['user_id' => $user->id])->delete();
        } else {
            $compareCategories = [
                'diamonds',
                'engagement-rings',
                'wedding-rings',
                'products'
            ];
            foreach ($compareCategories as $category) {
                Cookie::queue(Cookie::forget("compare-$category"));
            }
        }
        return response(['message' => trans('api.compares.removed_ok')]);
    }

    public function inCompares($id, $class)
    {
        $user = Auth::guard('api')->user();
        $compare = $user
            ? Compare::where(['user_id' => $user->id, 'product_type' => $class, 'product_id' => $id])
                ->exists()
            : array_search(
                $id,
                CommonHelper::getSavedItemsFromCookie("compare-".CommonHelper::getTypesByProductClass($class))
            );
        return ($compare !== false && $compare !== null);
    }

    // synchronize db with cookie after login

    public function synchronizeCompare($user)
    {
        $dbComparesAll = $this->getCompareItemsAll($user->id)
            ->groupBy(function($item){
                return get_class($item);
            });
        $cookieCompares = $this->getCompareItemsFromCookieAll()->groupBy(function($item){ return get_class($item);});

        foreach ($cookieCompares->reverse() as $class => $items) { // reverse to restore order by date asc
            $dbCompares = $dbComparesAll->get($class)? : collect();
            $countAvailable = 6 - intval($dbCompares->count());
            foreach ($items as $item) {
                if (!$dbCompares->contains($item)) {
                    if ( $countAvailable-- <= 0) {
                        continue;
                    }
                    $this->addItemToUserCompare($item, $user);
                }
            }
        }
    }

    public function synchronizeFavorites($user)
    {
        $dbFavoritesAll = $this->getUserLikes($user->id)->groupBy(function($item){ return get_class($item);});
        $cookieFavorites = $this->getFavoriteItemsFromCookieAll()->groupBy(function($item){ return get_class($item);});

        foreach ($cookieFavorites->reverse() as $class => $items) {
            $dbFavorites = $dbFavoritesAll->get($class)? : collect();
            $countAvailable = 6 - intval($dbFavorites->count());
            foreach ($items as $item) {
                if (!$dbFavorites->contains($item)) {
                    if ( $countAvailable-- <= 0) {
                        continue;
                    }
                    $item->like();
                }
            }
        }
    }

    // sharing

    public function saveFavoritesForSharing( $type = null )
    {
        $favorites = ($user = Auth::guard('api')->user())
            ? $this->getUserLikes($user->id)
            : $this->getFavoriteItemsFromCookieAll();

        if ($type) {
            $favoritesByType = $favorites->groupBy(function($item){ return get_class($item);});

            $favorites = isset($favoritesByType[CommonHelper::getProductClassByType($type)])
                ? $favoritesByType[CommonHelper::getProductClassByType($type)]
                : collect();
        }
        if ($favorites->isEmpty()) {
            return response(
                ['message' => trans('api.favorites.error.share_list_empty')],
                Response::HTTP_NOT_FOUND);
        }
        $shareListData = CommonHelper::saveShareList($favorites);
        if (request('email')) {
            return CommonHelper::shareListViaEmail($shareListData['id'],
                request('email'), request('share_path'));
        }

        return response(
            ['message' => trans('api.compares.email'), 'share_id'=>$shareListData['id']]);
    }

    public function saveComparesForSharing( $type = null )
    {
        $compares = ($user = Auth::guard('api')->user())
            ? $this->getCompareItemsAll($user->id)
            : $this->getCompareItemsFromCookieAll();
        if ($type) {
            $comparesByType = $compares->groupBy(function($item){ return get_class($item);});
            $compares = isset( $comparesByType[CommonHelper::getProductClassByType($type)])
                ? $comparesByType[CommonHelper::getProductClassByType($type)]
                : collect();
        }
        if ($compares->isEmpty()) {
            return response(
                ['message' => trans('api.compares.error.share_list_empty')],
                Response::HTTP_NOT_FOUND);
        }

        $shareListData =  CommonHelper::saveShareList($compares);
        if (request('email')) {
            return CommonHelper::shareListViaEmail(
                $shareListData['id'],
                request('email'), request('share_path'),
                'compares');
        }

        return response(
            ['message' => trans('api.compares.email'), 'share_id'=>$shareListData['id']]);

    }

}
