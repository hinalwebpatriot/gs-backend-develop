<?php


namespace GSD\Containers\Referral\Values;

use DebugBar\DebugBar;
use Exception;
use GSD\Ship\Exceptions\BadRequestHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Генерирует промокод
 *
 * Class GeneratePromoCodeValue
 * @package GSD\Containers\Referral\Values
 */
class GenerateReferralPromoCodeValue
{
    const DIG_MAX = 9999;

    private string  $firstName;
    private ?string $lastName;

    /**
     * @throws Exception
     */
    public function __construct(string $firstName, ?string $lastName)
    {
        $this->firstName = trim($firstName);
        if (Str::length($this->firstName) < 2) {
            throw new Exception('First Name so small');
        }
        $this->lastName = trim($lastName);
    }

    public function getValue(): string
    {
        return $this->getLetters().$this->getDigs();
    }

    /**
     * Генерирует буквенную часть кода
     *
     * @return string
     */
    private function getLetters(): string
    {
        $length = 2;
        $val = '';
        if ($this->lastName) {
            $val = substr($this->lastName,0, 1);
            $length--;
        }
        $val = substr($this->firstName,0, $length).$val;
        return Str::upper($val);
    }

    /**
     * Генерирует цифирную часть кода
     *
     * @return string
     */
    private function getDigs(): string
    {
        return Str::padLeft(rand(1, self::DIG_MAX), $this->getRank(), '0');
    }

    /**
     * Высчитывает количество разрядов максимального числа
     *
     * @return int
     */
    private function getRank(): int
    {
        $number = self::DIG_MAX;
        $rank = 0;
        do {
            $rank++;
            $number = $number / 10;
        } while ($number > 1);
        return $rank;
    }
}