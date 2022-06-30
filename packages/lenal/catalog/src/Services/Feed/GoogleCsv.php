<?php
namespace lenal\catalog\Services\Feed;


use Illuminate\Support\Str;

class GoogleCsv extends Feed
{
    protected $format = self::FORMAT_CSV;

    public function create()
    {
        $this->beginBlock();
        $this->buildItems();

        $this->buffer->close();
    }

    protected function beginBlock()
    {
        $this->buffer->add($this->formatLine([
            'id',
            'title',
            'description',
            'link',
            'condition',
            'price',
            'availability',
            'mpn',
            'image_link',
            'brand',
            'google_product_category',
        ]));
    }

    protected function addItem(array $item)
    {
        if (!$item['image'] || !$item['price']) {
            return ;
        }

        $this->buffer->add($this->formatLine([
            $this->csvFormat($item['id']),
            $this->csvFormat(Str::limit($item['title'], 150)),
            $this->csvFormat($item['description']),
            $this->csvFormat($item['url']),
            $this->csvFormat('new'),
            $this->csvFormat($item['price'] . ' AUD'),
            $this->csvFormat('stock'),
            $this->csvFormat($item['sku']),
            $this->csvFormat($item['image']),
            $this->csvFormat($item['brand']),
            $this->csvFormat($item['category']),
        ]));
    }

    protected function csvFormat($value)
    {
        $quote = '"';
        return $quote . str_replace($quote, $quote . $quote, $value) . $quote;
    }

    protected function formatLine($arr)
    {
        return implode(';', $arr);
    }
}