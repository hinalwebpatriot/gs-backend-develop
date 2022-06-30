<?php

namespace lenal\blocks\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicPage extends Model
{
    public $timestamps = false;

    protected $fillable = ['page'];

    public function blocks()
    {
        return $this->hasMany(StaticBlock::class);
    }

    public function guideBlock()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'guide');
    }

    public function certificateBlocks()
    {
        return $this->hasMany(StaticBlock::class)->where('block_type', 'certificate');
    }

    public function descriptionBlock()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'description');
    }

    public function promoBlocks()
    {
        return $this->hasMany(StaticBlock::class)->where('block_type', 'promo');
    }

    public function additionalInfoBlock()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'additional-info');
    }

    public function additionalInfoIcons()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'additional-info-icons');
    }

    public function slider()
    {
        return $this->hasMany(StaticBlock::class)->where('block_type', 'slider');
    }

    public function tagLinks()
    {
        return $this->hasMany(StaticBlock::class)->where('block_type', 'tag-links');
    }

    public function textBlock()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'simple-text');
    }

    public function recommendProducts()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'recommend-products');
    }

    public function completeLookBlock()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'complete-look');
    }

    public function occasionSpecial()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'occasion-special');
    }

    public function topPicks()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'top-picks');
    }

    public function secondRingsSlider()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'second-rings-slider');
    }

    public function storyCustomJewelry()
    {
        return $this->hasOne(StaticBlock::class)->where('block_type', 'story-custom-jewerly');
    }
}
