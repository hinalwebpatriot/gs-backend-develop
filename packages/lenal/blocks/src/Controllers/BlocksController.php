<?php

namespace lenal\blocks\Controllers;

use App\Http\Controllers\Controller;
use lenal\blocks\Facades\Blocks;

class BlocksController extends Controller
{
    public function certificate($page)
    {
        return !empty($data = Blocks::certificate($page))
            ? response()->json($data)
            : response()->noContent();
    }

    public function guide($page)
    {
        return !empty($data = Blocks::guide($page))
            ? response()->json($data)
            : response()->noContent();
    }

    public function diamondsDescription()
    {
        return !empty($data = Blocks::diamondsDescription())
            ? response()->json($data)
            : response()->noContent();
    }

    public function promo($page)
    {
        return !empty($data = Blocks::promo($page))
            ? response()->json($data)
            : response()->noContent();
    }

    public function additionalInfo($page)
    {
        return !empty($data = Blocks::additionalInfo($page))
            ? response()->json($data)
            : response()->noContent();
    }

    public function slider($page)
    {
        return !empty($data = Blocks::slider($page))
            ? response()->json($data)
            : response()->noContent();
    }

    public function contactsPage()
    {
        return Blocks::contactsPage();
    }

    public function recommendProducts($page)
    {
        return Blocks::recommendProducts($page);
    }

    public function completeLook()
    {
        return Blocks::completeLook();
    }

    public function occasionSlider()
    {
        return Blocks::occasionSlider();
    }

    public function secondRingsSlider()
    {
        return Blocks::secondRingsSlider();
    }

    public function topPicks($page)
    {
        return Blocks::topPicks($page);
    }

    public function storyCustomJewelry()
    {
        return Blocks::storyCustomJewelry();
    }
}
