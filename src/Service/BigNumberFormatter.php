<?php


namespace App\Service;


use App\Model\BigNumber;

class BigNumberFormatter
{
    public function format(BigNumber $number)
    {
        if ('0' === $number->getFractional()) {
            return $number->getInteger();
        }

        return $number->getInteger().BigNumber::FRACTIONAL_SEPARATOR.$number->getFractional();
    }
}