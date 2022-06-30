<?php

namespace lenal\catalog\Services\Imports;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use lenal\catalog\Jobs\ProductImageFromPath;
use lenal\catalog\Jobs\ProductVideoFromPath;
use lenal\catalog\Models\Diamonds\Shape;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Products\ProductSize;
use lenal\catalog\Models\Products\ProductStyle;

abstract class ProductsImport extends BaseImport
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    private $sizes;
    private $stoneShapes = [];
    private $productStyles = [];

    protected $attributes = [
        'slug' => '',
        'sku' => '',
        'group_sku' => '',
        'category_id' => '',
        'brand_id' => '',
        'metal_id' => '',
        'style_id' => '',
        'shape_id' => '',
        'gender' => '',
        'item_name' => '',
        'description' => '',
        'header' => '',
        'sub_header' => '',
        'raw_price' => '',
        'cost_price' => '',
        'discount_price' => '',
        'inc_price' => '',
        'stone_size' => '',
        'setting_type' => '',
        'side_setting_type' => '',
        'min_size_id' => '',
        'max_size_id' => '',
        'carat_weight' => '',
        'average_ss_colour' => '',
        'average_ss_clarity' => '',
        'approx_stones' => '',
        'band_width' => '',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->sizes = ProductSize::asArray();
        $this->stoneShapes = Shape::query()->get()->pluck('id', 'slug')->toArray();
        $this->productStyles = ProductStyle::query()->get()->pluck('id', 'slug')->toArray();
    }

    public function run()
    {
        foreach ($this->getItems() as $i => $item) {
            $this->saveItem($this->formatData($item));
        }
    }

    protected function findOrCreateSize($searchSize)
    {
        if (!$searchSize) {
            return [];
        }

        $size = $this->sizes->first(function (array $size) use ($searchSize) {
            return (float)$size['slug'] == (float)$searchSize;
        });

        if (!$size) {
            /** @var ProductSize $sizeModel */
            $sizeModel = ProductSize::query()->create([
                'slug' => (float)$searchSize,
                'title' => ['en' => $searchSize],
            ]);

            $this->sizes->push($sizeModel->map());

            return $sizeModel->map();
        }

        return $size;
    }

    protected function getShapeId($shape)
    {
        return $this->stoneShapes[strtolower($shape)] ?? 0;
    }

    protected function getOrCreateStyleId($style)
    {
        $styleSlug = mb_strtolower($style, 'utf-8');

        if (isset($this->productStyles[$styleSlug])) {
            return $this->productStyles[$styleSlug];
        }

        /** @var ProductStyle $productStyle */
        try {
            $productStyle = ProductStyle::query()->create([
                'slug' => $styleSlug,
                'title' => ['en' => $style],
            ]);

            $this->productStyles[$productStyle->slug] = $productStyle->id;

            return $productStyle->id;
        } catch (\Exception $e) {
            logger()->channel('import-prod')->debug([$styleSlug, $style]);
            logger()->channel('import-prod')->debug($e->getMessage());
        }

        return '';
    }

    protected function saveItem(array $item)
    {
        if (!$item) {
            return;
        }

        $validator = Validator::make($item, [
            'slug' => 'required',
            //'sku' => 'required|unique:products',
            'category_id' => 'required',
            'item_name' => 'required',
            'brand_id' => 'required',
            'style_id' => 'required',
            'shape_id' => 'required',
            'metal_id' => 'required',
        ], [
            'unique' => 'The :attribute (:input) has already been taken.'
        ]);

        if ($validator->fails()) {
            logger()->channel('import-prod')->debug([
                'product' => $item,
                'errors' => $validator->errors()->toArray(),
            ]);
            return;
        }

        $product = Product::query()
            ->where('slug', $item['slug'])
            ->first();

        if (!$product) {
            $product = new Product();
        }

        try {
            $product->fill($item);
            $product->save();
        } catch (\Exception $e) {
            logger()->channel('import-prod')->debug($item);
            logger()->channel('import-prod')->debug($e->getMessage());
            return;
        }

        if ($product->id && isset($item['files']) && $item['files']) {
            $images = $this->extractImages($item['files']);
            if ($images) {
                ProductImageFromPath::dispatch($product->id, $images);
            }

            $videos = $this->extractVideos($item['files']);
            if ($videos) {
                ProductVideoFromPath::dispatch($product->id, $videos);
            }
        }
    }

    protected function parseBrand($str)
    {
        return $brand = trim(explode(' ', $str)[0] ?? '');
    }

    protected function parseCarat($value)
    {
        return str_replace([' ', 'cts', 'ct'], '', $value);
    }

    protected function getGroupSku($name, $shape, $stoneSize, $settingType)
    {
        return collect([
            Str::kebab($name),
            Str::kebab($shape),
            number_format($stoneSize, 2),
            Str::kebab($settingType),
        ])->implode('/');
    }

    public function createStatistic()
    {
        $result = [];

        foreach ($this->getItems() as $i => $item) {
            $formatted = $this->formatData($item);
            $statusData = [];

            if (!isset($formatted['files'])) {
                continue;
            }

            if ($formatted['files']) {
                $images = $this->extractImages($formatted['files']);
                $videos = $this->extractVideos($formatted['files']);
                $statusData[] = 'images: ' . count($images);
                $statusData[] = 'videos: ' . count($videos);
            } else {
                $statusData[] = 'no images, no videos';
            }

            $result[] = [
                'sku' => $formatted['sku'],
                'gs_stock_no' => $formatted['stock_number'],
                'status' => implode('|', $statusData),
            ];
        }

        $contents = '';
        $handle = fopen('php://temp', 'r+');

        if (!$result) {
            return;
        }

        foreach ([array_keys($result[0])] + $result as $line) {
            fputcsv($handle, $line, ';');
        }

        rewind($handle);

        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }
        fclose($handle);

        $info = pathinfo($this->path);

        $filename = $info['filename'] . '-stat.csv';

        file_put_contents($info['dirname'] . '/' . $filename, $contents);
    }
}