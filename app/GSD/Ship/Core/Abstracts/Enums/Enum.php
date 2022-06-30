<?php

namespace GSD\Core\Abstracts\Enums;


use MyCLabs\Enum\Enum as MCEnum;

/**
 * Class Enum
 * @package GSD\Core\Abstracts\Enums
 */
abstract class Enum extends MCEnum
{
    /**
     * Возвращает массив соответствий константы и ее описания
     *
     * [
     *     self::CONST_FOO => 'Constant description'
     * ]
     *
     * @return array
     */
    abstract protected function data(): array;

    /**
     * Возвращает описание константы
     *
     * @return string
     */
    public function label(): string
    {
        if (isset($this->data()[$this->value])) {
            return $this->data()[$this->value];
        }
        return '';
    }

    /**
     * Возвращает список значений констант и их описаний
     *
     * @return array
     */
    public static function labels(): array
    {
        $data = [];
        foreach (static::toArray() as $val) {
            $data[$val] = (new static($val))->label();
        }
        return $data;
    }
}
