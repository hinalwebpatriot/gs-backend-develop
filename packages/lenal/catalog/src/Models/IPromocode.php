<?php


namespace lenal\catalog\Models;


interface IPromocode
{
    public function getSku();
    public function getCollectionId();
    public function getCategorySlug();
}