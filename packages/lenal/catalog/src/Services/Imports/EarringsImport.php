<?php

namespace lenal\catalog\Services\Imports;


class EarringsImport extends ProductsImport
{
    protected $filename = 'earrings.csv';

    protected function formatData(array $data)
    {
        if (!$data[1] || !$data[3] || !$data[26]) {
            return [];
        }

        $brand = 'GSD';

        $itemName = $this->encodingValue($data[1]);
        $shape = trim($data[4]);
        $stoneSize = (float) $this->parseCarat($data[6]);
        $metalAbbr = $this->getMetalAbbreviation($data[13]);
        $incPrice = $this->formatPrice($data[24]);
        $sku = 'GSE' . $data[26];

        return [
            'slug' => $sku,
            'sku' => $sku,
            'group_sku' => $this->getGroupSku($itemName, $shape, $stoneSize, $data[10]),
            'category_id' => 1,
            'brand_id' => $this->getOrCreateBrandId($brand),
            'min_size_id' => 0,
            'max_size_id' => 0,
            'stone_size' => $stoneSize,
            'metal_id' => $this->getMetalId($data[13]),
            'style_id' => $this->getOrCreateStyleId($data[11]),
            'shape_id' => $this->getShapeId($shape),
            'item_name' => ['en' => $itemName],
            'raw_price' => ceil($incPrice / 1.1),
            'inc_price' => $incPrice,
            'setting_type' => $data[10],
            //'side_setting_type' => $data[12],
            'approx_stones' => $data[17],
            'carat_weight' => $this->parseCarat($data[18]),
            'average_ss_colour' => $data[19],
            'average_ss_clarity' => $data[20],
            'stock_number' => $data[26],
            'files' => $this->collectImages('earrings/' . $data[26]),
        ];
    }
}