<?php

namespace lenal\catalog\Contracts;

interface FilterBuilder
{
    public function addToFilter(Builder $wedding, Request $request): void;
}