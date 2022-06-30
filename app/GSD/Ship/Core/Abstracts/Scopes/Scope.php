<?php


namespace GSD\Core\Abstracts\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Scope
 * @package GSD\Core\Abstracts\Scopes
 */
abstract class Scope implements \Illuminate\Database\Eloquent\Scope
{
    abstract public function apply(Builder $builder, Model $model);
}