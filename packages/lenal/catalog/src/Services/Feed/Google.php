<?php
namespace lenal\catalog\Services\Feed;


use Illuminate\Support\Str;
use lenal\catalog\Resources\ImageResource;

class Google extends Feed
{
    protected $format = self::FORMAT_XML;

    public function create()
    {
        $this->beginBlock();
        $this->buildItems();
        $this->endBlock();

        $this->buffer->close();
    }

    protected function beginBlock()
    {
        $this->buffer->add('<?xml version="1.0" ?'.'>');
        $this->buffer->add('<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0" >');
        $this->buffer->add('<channel>');
        $this->buffer->addWithTab('<title>GS Diamonds</title>');
        $this->buffer->addWithTab('<link>'.trim($this->baseUrl, '/').'</link>');
        $this->buffer->addWithTab('<description>GS Diamonds Proud to be an Australian owned family business</description>');
    }

    protected function addItem(array $item)
    {
        if (!$item['image'] || !$item['price']) {
            return ;
        }

        $this->buffer->addWithTab('<item>');
        $this->buffer->addWithTab('<g:id>' . $item['id'] . '</g:id>', 2);
        $this->buffer->addWithTab('<g:title>' . Str::limit($item['title'], 150) . '</g:title>', 2);
        $this->buffer->addWithTab('<g:description>' . $this->prepareString($item['description']) . '</g:description>', 2);
        $this->buffer->addWithTab('<g:link>' . $item['url'] . '</g:link>', 2);
        $this->buffer->addWithTab('<g:condition>new</g:condition>', 2);
        $this->buffer->addWithTab('<g:price>' . $item['price'] . ' AUD</g:price>', 2);
        $this->buffer->addWithTab('<g:availability>in stock</g:availability>', 2);
        $this->buffer->addWithTab('<g:mpn>' . $item['sku'] . '</g:mpn>', 2);
        $this->buffer->addWithTab('<g:image_link>' . $item['image'] . '</g:image_link>', 2);

        if ($item['brand']) {
            $this->buffer->addWithTab('<g:brand>' . $this->prepareString($item['brand']) . '</g:brand>', 2);
        }

        if ($item['category']) {
            $this->buffer->addWithTab('<g:google_product_category>'.$this->prepareString($item['category']).'</g:google_product_category>', 2);
        }

        $this->buffer->addWithTab('</item>');
    }

    protected function endBlock()
    {
        $this->buffer->add('</channel>');
        $this->buffer->add('</rss>');
    }
}