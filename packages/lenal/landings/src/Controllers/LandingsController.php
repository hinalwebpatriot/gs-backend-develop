<?php

namespace lenal\landings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use lenal\landings\Models\Landing;
use lenal\landings\Repositories\LandingRepository;


class LandingsController extends Controller
{
    public function index($slug)
    {
        /** @var Landing $landing */
        $landing = Landing::query()
            ->with(['rings', 'diamonds'])
            ->where('slug', $slug)
            ->first();

        if (!$landing) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return (new LandingRepository())->fetchAll($slug);
    }
}
