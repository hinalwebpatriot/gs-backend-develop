<?php

namespace lenal\catalog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use lenal\catalog\Facades\CartHelper;
use lenal\catalog\Facades\OrderHelper;
use lenal\catalog\Facades\CommonHelper;
use lenal\catalog\Facades\FavoritesCompareHelper;
use lenal\catalog\Requests\InvoiceRequest;
use lenal\catalog\Requests\ProductHintRequest;
use lenal\catalog\Requests\SendShareLinkRequest;
use lenal\catalog\Requests\ShareCompleteRingsRequest;
use lenal\catalog\Requests\StoreOrderPostRequest;
use lenal\catalog\Requests\CartGetRequest;



class CommonController extends Controller
{

    public function favorites($type = null)
    {
        return FavoritesCompareHelper::favorites($type);
    }

    public function addToFavorites($type)
    {
        return FavoritesCompareHelper::addToFavorites(request('id'), $type);
    }

    public function removeFromFavorites($type)
    {
        return FavoritesCompareHelper::removeFromFavorites(request('id'), $type);
    }

    public function removeAllFavorites($type = null)
    {
        return FavoritesCompareHelper::removeAllFavorites($type);
    }

    public function compares()
    {
        return FavoritesCompareHelper::comparesList();
    }

    public function removeFromCompare($type)
    {
        return FavoritesCompareHelper::removeFromCompare(request('id'), $type);
    }

    public function addToCompare($type)
    {
        return FavoritesCompareHelper::addToCompare(request('id'), $type);
    }

    public function comparesList($type)
    {
        return FavoritesCompareHelper::comparesList($type);
    }

    public function removeAllCompares($type = null)
    {
        return FavoritesCompareHelper::removeAllCompares($type);
    }


    public function addToCart($type)
    {
        return CartHelper::addToCart(request('id'), $type, request('size_slug'), request('engraving'));
    }

    public function getCart()
    {
        return CartHelper::getCart();
    }

    public function getAllCart(CartGetRequest $request)
    {
        return CartHelper::getAllCart($request['coupon']);
    }

    public function removeFromCart($type)
    {
        return CartHelper::removeFromCart(request('id'), $type,request('size_slug'));
    }

    public function createOrder(StoreOrderPostRequest $request)
    {
        return OrderHelper::createOrder($request);
    }

    public function createOrderInvoice(InvoiceRequest $request)
    {
        return OrderHelper::createInvoiceOrder($request->get('invoice_id'));
    }

    public function createOrderPaypal()
    {
        return OrderHelper::createOrderPaypal();
    }

    public function getOrder($id)
    {
        return OrderHelper::getOrder($id);
    }

    public function getOrderByToken($token)
    {
        return OrderHelper::getOrderByToken($token);
    }

    public function getUserPicks($type)
    {
        return CommonHelper::getUserPicks($type);
    }

    public function getUserAllPicks()
    {
        return CommonHelper::getUserAllPicks();
    }

    public function getViewedItems($type)
    {
        return CommonHelper::viewedItems($type);
    }

    public function favoritesSave()
    {
        return FavoritesCompareHelper::saveFavoritesForSharing();
    }

    public function favoritesShare($type, SendShareLinkRequest $request)
    {
        return FavoritesCompareHelper::saveFavoritesForSharing($type);
    }

    public function getShareList($id)
    {
        return CommonHelper::getShareList($id);
    }

    public function comparesSave()
    {
        return FavoritesCompareHelper::saveComparesForSharing();
    }

    public function comparesShare($type, SendShareLinkRequest $request)
    {
        return FavoritesCompareHelper::saveComparesForSharing($type);
    }

    public function getSuggestsHomepage()
    {
        return CommonHelper::getSuggestsHomepage();
    }

    public function sendProductHint(ProductHintRequest $request)
    {
        return CommonHelper::sendProductHint($request);
    }

    public function shareCompleteRings()
    {
        return CommonHelper::shareCompleteRings();
    }

    public function shareCompleteRingsMail(ShareCompleteRingsRequest $request)
    {
        return CommonHelper::shareCompleteRingsMail();
    }

    public function shareCompleteRingsList(int $id)
    {
        return CommonHelper::shareCompleteRingsList($id);
    }

    public function getFilterDescriptions()
    {
        return CommonHelper::getFilterDescriptions();
    }

    public function homepage360Sliders()
    {
        return CommonHelper::homepage360Sliders();
    }
}