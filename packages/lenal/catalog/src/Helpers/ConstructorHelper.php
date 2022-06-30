<?php

namespace lenal\catalog\Helpers;

use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use lenal\catalog\Models\CompleteRing;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;
use lenal\catalog\Resources\CompleteRingResource;
use lenal\catalog\Facades\CartHelper;
use lenal\catalog\Facades\CommonHelper;

class ConstructorHelper
{
    protected $ring_diamond_carat;
    protected static $default_ring_diamond_carat = [
        '0.3'  => [
            'min' => '0',
            'max' => '0.4',
        ],
        '0.5'  => [
            'min' => '0.41',
            'max' => '0.64',
        ],
        '0.75' => [
            'min' => '0.65',
            'max' => '0.84',
        ],
        '1'    => [
            'min' => '0.85',
            'max' => '1.2',
        ],
        '1.5'  => [
            'min' => '1.21',
            'max' => '1.75',
        ],
        '2'    => [
            'min' => '1.76',
            'max' => '2.4',
        ],
        '2.5'    => [
            'min' => '2.41',
            'max' => '30',
        ],
    ];

    public function __construct()
    {
        $this->ring_diamond_carat = config('catalog.constructor.ring_diamond_carat', self::$default_ring_diamond_carat);
    }

    public function findSuitableRingCarat($input_carat)
    {
        foreach ($this->ring_diamond_carat as $ring_carat => $diamond_carat) {
            if ($input_carat >= $diamond_carat['min'] && $input_carat <= $diamond_carat['max']) {
                return $ring_carat;
            }
        }

        return null;
    }

    public function findSuitableDiamondCarat($input_carat)
    {
        $input_carat = rtrim($input_carat, '.0');
        if (array_key_exists($input_carat, $this->ring_diamond_carat)) {
            return $this->ring_diamond_carat[$input_carat];
        }

        return null;
    }

    public function matchProducts(Diamond $diamond, EngagementRing $ring)
    {
        if ($diamond->shape_id == $ring->stone_shape_id) {
            return response(['message' => trans('api.constructor.products_match')], Response::HTTP_OK);
        }

        return response(['message' => trans('api.constructor.products_not_match')], Response::HTTP_NOT_ACCEPTABLE);
    }

    public function saveCompletedRing($diamond_id, $ring_id, $ring_size, $engraving = null)
    {
        $user_id = Auth::guard('api')->id();
        if ($user_id) {
            $ringData = [
                'user_id'    => $user_id,
                'diamond_id' => $diamond_id,
                'ring_id'    => $ring_id,
                'ring_size'  => $ring_size,
            ];

            if (CompleteRing::query()->where($ringData)->count() > 0) {
                return response(['message' => trans('api.constructor.complete_ring_already_exists')], Response::HTTP_CONFLICT);
            }

            $ringData['engraving'] = Arr::get($engraving, 'text');
            $ringData['engraving_font'] = Arr::get($engraving, 'font');

            CompleteRing::query()->create($ringData);

            return response(['message' => trans('api.constructor.complete_ring_created')], Response::HTTP_OK);
        }

        $complete_rings_json = request()->cookie('complete_rings');
        $complete_rings_array = $complete_rings_json? json_decode($complete_rings_json, true): [];
        $completeRing = [
            $diamond_id,
            $ring_id,
            $ring_size
        ];
        if (($index = array_search($completeRing, $complete_rings_array)) !== false) {
            return response(['message' => trans('api.constructor.complete_ring_already_exists')], Response::HTTP_CONFLICT)
                ->withCookie(cookie()->forever('complete_rings', json_encode($complete_rings_array)));
        }

        $completeRing[] = Arr::get($engraving, 'text');
        $completeRing[] = Arr::get($engraving, 'font');
        $complete_rings_array[] = $completeRing;

        return response(['message' => trans('api.constructor.complete_ring_created')], Response::HTTP_OK)
            ->withCookie(cookie()->forever('complete_rings', json_encode($complete_rings_array)));
    }

    public function updateCompleteRing($completeRingId, $diamond_id = null, $ring_id = null, $ring_size = null, $engraving = null)
    {
        if ($user_id = Auth::guard('api')->id()) {

            $ring = CompleteRing::query()->where('id', $completeRingId)->first();
            if (!$ring) {
                return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
            }

            $updatedFields = [
                'diamond_id' => $diamond_id ?: $ring->diamond_id,
                'ring_id' => $ring_id ?: $ring->ring_id,
                'ring_size' => $ring_size ?: $ring->ring_size,
                'engraving' => $engraving['text'] ?? $ring->engraving,
                'engraving_font' => $engraving['font'] ?? $ring->engraving_font,
            ];

            $ring->update($updatedFields);

            return response(['message' => trans('api.constructor.complete_ring_updated')], Response::HTTP_OK);
        }

        $complete_rings_json = request()->cookie('complete_rings');
        $complete_rings_array = $complete_rings_json? json_decode($complete_rings_json, true): [];

        if (!array_key_exists($completeRingId, $complete_rings_array)) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND)
                ->withCookie(cookie()->forever('complete_rings', json_encode($complete_rings_array)));
        }

        $ring = $complete_rings_array[$completeRingId];
        $complete_rings_array[$completeRingId] = [
            $diamond_id ?: $ring[0],
            $ring_id ?: $ring[1],
            $ring_size ?: $ring[2],
            $engraving['text'] ?? ($ring[3] ?? null),
            $engraving['font'] ?? ($ring[4] ?? null),
        ];

        return response(['message' => trans('api.constructor.complete_ring_updated')], Response::HTTP_OK)
            ->withCookie(cookie()->forever('complete_rings', json_encode($complete_rings_array)));
    }

    public function getUserCompleteRings($user_id)
    {
        if ($complete_rings_array = json_decode(request()->cookie('complete_rings'), true)) {
            $complete_rings_from_db = CompleteRing::query()->where('user_id', $user_id)
                ->get(['user_id', 'diamond_id', 'ring_id', 'ring_size', 'engraving', 'engraving_font'])
                ->toArray();

            foreach ($complete_rings_array as $item) {
                $ringCookie = [
                    'user_id' => $user_id,
                    'diamond_id' => $item[0],
                    'ring_id' => $item[1],
                    'ring_size' => $item[2]
                ];

                if (array_search($ringCookie, $complete_rings_from_db) === false) {
                    $ringCookie['engraving'] = $item[3] ?? null;
                    $ringCookie['engraving_font'] = $item[4] ?? null;
                    CompleteRing::query()->create($ringCookie);
                }
            }
        }

        return response([
            'data'=> CompleteRingResource::collection(CompleteRing::query()->where('user_id', $user_id)->get())
        ])->withCookie(cookie()->forget('complete_rings'));
    }

    public function getCompleteRingsFromCookie()
    {
        $complete_rings_collection = collect(json_decode(request()->cookie('complete_rings'), true));
        return $complete_rings_collection->map(function ($item, $index) {
            $cookieRing = CompleteRing::query()->make([
                'diamond_id' => $item[0],
                'ring_id' => $item[1],
                'ring_size' => $item[2],
                'engraving' => $item[3] ?? '',
                'engraving_font' => $item[4] ?? '',
            ]);

            $cookieRing->id = $index;

            return $cookieRing;
        });
    }

    public function getCompleteRings()
    {
        if ($user_id = Auth::guard('api')->id()) {
            return $this->getUserCompleteRings($user_id);
        }

        $complete_rings_collection = $this->getCompleteRingsFromCookie();

        $result =  array_filter(CompleteRingResource::collection($complete_rings_collection)->resolve(), function($completeItem) {
            return !!$completeItem;
        });

        return [
            'data' => array_values($result)
        ];
    }

    public function deleteCompleteRing($completeRingId)
    {
        if ($user_id = Auth::guard('api')->id()) {
            $ring = CompleteRing::where('id', $completeRingId)->first();
            if (!$ring) {
                return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
            }
            $ring->delete();
            return response(['message' => trans('api.constructor.complete_ring_deleted')], Response::HTTP_OK);
        }

        $complete_rings_array = json_decode(request()->cookie('complete_rings'), true)?:[];
        if (!array_key_exists($completeRingId, $complete_rings_array)) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND)
                ->withCookie(cookie()->forever('complete_rings', json_encode($complete_rings_array)));
        }
        unset($complete_rings_array[$completeRingId]);

        return response(['message' => trans('api.constructor.complete_ring_deleted')], Response::HTTP_OK)
            ->withCookie(cookie()->forever('complete_rings', json_encode($complete_rings_array)));

    }

    public function addToCart($completeRingId)
    {
        if (Auth::guard('api')->id()) {
            // update db cart
            $ring = CompleteRing::query()->where('id', $completeRingId)->first();

            if (!$ring) {
                return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
            }

            CartHelper::addToCart($ring->ring_id, 'engagement-rings', $ring->ring_size, [
                'text' => $ring->engraving,
                'font' => $ring->engraving_font,
            ]);
            CartHelper::addToCart($ring->diamond_id, 'diamonds');

            return response(['message' => trans('api.constructor.added_to_cart')]);
        }

        // update cookie cart
        $complete_rings_array = json_decode(request()->cookie('complete_rings'), true)? : [];
        $ring = (array_key_exists($completeRingId, $complete_rings_array))
            ? $complete_rings_array[$completeRingId]
            : null;
        if (!$ring) {
            return response(['message' => trans('api.error.not_found')], Response::HTTP_NOT_FOUND);
        }

        $cart = CommonHelper::getSavedItemsFromCookie('cart');
        $cartConstructor = [
            [
                'id' => $ring[0],
                'type' => 'diamonds'
            ],
            [
                'id' => $ring[1],
                'type' => 'engagement-rings',
                'size_slug' => $ring[2],
                'engraving' => $ring[3] ?? null,
                'engraving_font' => $ring[4] ?? null,
            ]
        ];
        foreach ($cartConstructor as $cartItem) {
            if (array_search($cartItem, $cart) === false) {
                $cart[] = $cartItem;
            }
        }

        return response(['message' => trans('api.constructor.added_to_cart')])->withCookie(
            cookie()->forever('cart', json_encode($cart))
        );
    }
}
