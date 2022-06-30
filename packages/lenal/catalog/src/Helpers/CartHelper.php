<?php

namespace lenal\catalog\Helpers;

use App\Helpers\ArrHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Resources\CartItemsCollection;
use lenal\catalog\Facades\CommonHelper as CommonHelper;
use lenal\catalog\Services\Promocode\PromocodeService;

class CartHelper
{
    public function addToCart(int $id, $type, $size_slug = null, $engraving = null)
    {
        // check if item exists
        $productClass = CommonHelper::getProductClassByType($type);
        if(!$item = $productClass::whereId($id)->first()) {
            return response(
                ['message' => trans('api.cart.error.item_not_found')],
                Response::HTTP_NOT_FOUND);
        }

        // add to cart
        if ($user = Auth::guard('api')->user()) {
            // update db cart

            // check if item is already added
            $cart = CartItem::query()
                ->whereHas('user', function ($userQuery) use ($user) {
                    $userQuery->where('id', $user->id);
                })
                ->where(['product_id'=>$id, 'product_type'=>$productClass, 'size_slug' => $size_slug, 'order_id' =>null])
                ->first();
            if ($cart) {
                return response(
                    ['message' => trans('api.cart.error.item_already_added')],
                    Response::HTTP_NOT_FOUND);
            }
            $this->addItemToUserCart($item, $user, $size_slug, $engraving);

            return response(['message' => trans('api.cart.item_added')]);
        }
        // update cookie cart
        $cart = CommonHelper::getSavedItemsFromCookie('cart');

        $cartItem = [
            'id' => $id,
            'type' => $type,
            'size_slug' => $size_slug,
        ];

        if (ArrHelper::exist($cart, $cartItem)) {
            return response(
                ['message' => trans('api.cart.error.item_already_added')],
                Response::HTTP_NOT_FOUND);
        }

        $cart[] = array_merge($cartItem, [
            'engraving' => $engraving['text'] ?? null,
            'engraving_font' => $engraving['font'] ?? null,
        ]);

        return response(['message' => trans('api.cart.item_added')])->withCookie(
            cookie()->forever('cart', json_encode($cart))
        );
    }

    private function addItemToUserCart($item, $user, $size_slug = null, $engraving = null)
    {
        if ($item != null) {
            $cart = new CartItem();
            $cart->size_slug = $size_slug;
            $cart->engraving = Arr::get($engraving, 'text');
            $cart->engraving_font = Arr::get($engraving, 'font');
            $cart->user()->associate($user);
            $item->carts()->save($cart);
        }
    }

    public function removeFromCart($id, $type, $size_slug = null)
    {
        // check if item exists
        $productClass = CommonHelper::getProductClassByType($type);
        if(!$item = $productClass::whereId($id)->first()) {
            return response(
                ['message' => trans('api.cart.error.item_not_found')],
                Response::HTTP_NOT_FOUND);
        }

        if ($user = Auth::guard('api')->user()) {
            CartItem
                ::whereHas('user', function ($userQuery) use ($user) { $userQuery->where('id', $user->id);})
                ->where(['product_id'=>$id, 'product_type'=>$productClass, 'size_slug' => $size_slug, 'order_id' =>null])
                ->delete();
            return response(['message' => trans('api.cart.item_removed')]);
        }

        $cart = CommonHelper::getSavedItemsFromCookie('cart');

        $cart = array_filter($cart, function($item) use ($id, $type, $size_slug) {
            return $item['id'] != $id || $item['type'] != $type || $item['size_slug'] != $size_slug;
        });

        return response(['message' => trans('api.cart.item_removed')])->withCookie(
            cookie()->forever('cart', json_encode($cart))
        );
    }

    public function getCart()
    {
        $user = Auth::guard('api')->user();

        $items = $user
            ? $this->getCartItems($user)
            : $this->getCartItemsFromCookie();

        return new CartItemsCollection($items->filter(function ($item){ return $item != null;}));
    }

    private function getCartItems($user)
    {
        $collection = CartItem::whereHas('user', function ($userQuery) use ($user) {
                $userQuery->where('id', $user->id);
            })
            ->where(['order_id'=>null])
            ->get()
            ->map(function ($cartItem) {
                return $cartItem->product_type
                    ::withCalculatedPrice()
                    ->where('id', $cartItem->product_id)
                    ->get()
                    ->each(function($item) use ($cartItem) {
                        $item->size_slug = $cartItem->size_slug;
                    })
                    ->first();
            });

        return (new PromocodeService())->apply($collection);
    }

    public function getCartItemsFromCookie()
    {
        $cart = CommonHelper::getSavedItemsFromCookie("cart");
        $items = [];
        foreach ($cart as $cartItem) {
            $items[] = CommonHelper::getProductClassByType($cartItem['type'])::withCalculatedPrice()
                ->where('id', $cartItem['id'])
                ->get()
                ->each(function($item) use ($cartItem) {
                    $item->size_slug = $cartItem['size_slug'] ?? null;
                    $item->engraving = $cartItem['engraving'] ?? null;
                    $item->engraving_font = $cartItem['engraving_font'] ?? null;
                })
                ->first();
        }

        return (new PromocodeService())->apply(collect(array_values($items)));
    }
    
    public function synchronizeCart($user)
    {
        $dbCart = $this->getCartItems($user);
        $cookieCart = $this->getCartItemsFromCookie();

        foreach ($cookieCart as $item) {
            if (!$dbCart->contains($item)) {
                $this->addItemToUserCart($item, $user, $item->size_slug, [
                    'text' => $item->engraving,
                    'font' => $item->engraving_font,
                ]);
            }
        }
    }

    public function clearCart()
    {
        if ($user = Auth::guard('api')->user()) {
            return !!CartItem::query()
                ->where([['order_id', null], ['user_id', $user->id]])
                ->delete();
        }
        cookie()->forget('cart');
        return true;
    }
}
