<?php

namespace lenal\catalog\Repositories;


use lenal\catalog\Models\Rings\Metal;

class MetalRepository
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $collection = [];

    public function __construct()
    {
        $this->loadCollection();
    }

    public function get($id)
    {
        return $this->collection->get($id);
    }

    private function loadCollection()
    {
        $data = cache()->remember('metal-collection', 60, function() {
            $collection = [];
            /** @var Metal $metal */
            foreach (Metal::query()->with('media')->get()->keyBy('id') as $metal) {

                $image = $metal->getMedia('image')->first();

                $collection[$metal->id] = [
                    'id' => $metal->id,
                    'title'  => $metal->title,
                    'slug'   => $metal->slug,
                    'image'  => $image ? $image->getFullUrl() : null
                ];
            }

            return $collection;
        });

        $this->collection = collect($data);
    }
}