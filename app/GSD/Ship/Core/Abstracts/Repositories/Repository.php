<?php


namespace GSD\Core\Abstracts\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 * @package GSD\Core\Abstracts\Repositories
 */
abstract class Repository
{
    /**
     * Инстанс модели с которой работает репозиторий
     * @var Model
     */
    protected Model $model;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->model = app($this->modelName());
    }

    /**
     * Метод должен возвращать полное имя класса модели
     * Model::class
     *
     * @return string
     */
    abstract public function modelName(): string;

    /**
     * Метод с которого нужно начинать запрос из модели
     * $this->startConditions()->where('id', $id)->first();
     *
     * @return Model|Builder
     */
    protected function startConditions(): Model
    {
        return clone $this->model;
    }
}