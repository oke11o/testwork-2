<?php

namespace App\Service;

use App\Exception\CalculatorException;

class Calculator
{
    const INTEGER_PART = 'INTEGER_PART';
    const FRACTIONAL_PART = 'FRACTIONAL_PART';
    /**
     * @var string
     */
    private $fractionalSeparator;

    /**
     * Calculator constructor.
     * @param string $fractionalSeparator
     */
    public function __construct($fractionalSeparator = '.')
    {
        $this->fractionalSeparator = $fractionalSeparator;
    }

    /**
     * @param string $base
     * @param string $add
     * @return string
     * @throws CalculatorException
     */
    public function sum(string $base, string $add): string
    {
        $this->assertArgument($base);
        $this->assertArgument($add);

        [$baseInteger, $baseFractional] = $this->splitOperand($base);
        [$addInteger, $addFractional] = $this->splitOperand($add);

        [$baseInteger, $addInteger] = $this->padParts($baseInteger, $addInteger, self::INTEGER_PART);
        [$baseFractional, $addFractional] = $this->padParts($baseFractional, $addFractional, self::FRACTIONAL_PART);


        [$fractional, $addDec] = $this->sumInteger($baseFractional, $addFractional);
        [$integer, $addDec] = $this->sumInteger($baseInteger, $addInteger, $addDec);
        if ($addDec) {
            $integer = '1' . $integer;
        }

        $fractional = rtrim($fractional, '0');
        $integer = ltrim($integer, '0');
        if ('' === $integer) {
            $integer = '0';
        }

        if ($fractional === '') {
            return $integer;
        }

        return $integer.$this->fractionalSeparator.$fractional;
    }

    /**
     * @param string $operand
     * @return array
     */
    private function splitOperand(string $operand): array
    {
        $result = explode($this->fractionalSeparator, $operand);
        $integer = array_shift($result);
        $fractional = array_shift($result);

        if (!$integer) {
            $integer = '0';
        }
        if (!$fractional) {
            $fractional = '0';
        }

        return [$integer, $fractional];
    }

    /**
     * @param $operand
     * @throws CalculatorException
     */
    private function assertArgument($operand): void
    {
        if (!is_numeric($operand)) {
            throw new CalculatorException(
                sprintf(
                    'Operand should be a numeric value, "%s" given.',
                    is_object($operand) ? get_class($operand) : gettype($operand)
                )
            );
        }
    }

    private function padParts(string $base, string $add, $part)
    {
        $lenBase = \strlen($base);
        $lenAdd = \strlen($add);
        if ($lenAdd === $lenBase) {
            return [$base, $add];
        }

        if ($lenBase < $lenAdd) {
            [$base, $add] = [$add, $base];
            $lenBase = $lenAdd;
        }

        if ($part === self::INTEGER_PART) {
            $add = str_pad($add, $lenBase, '0', STR_PAD_LEFT);
        } else {
            $add = str_pad($add, $lenBase, '0',STR_PAD_RIGHT);
        }

        return [$base, $add];
    }

    private function sumInteger($operand1, $operand2, $addInt = false): array
    {
        if (0 == $operand1) {
            if ($addInt) {
                return ['1', false];
            }
            return ['0', false];
        }
        $splitLength = 10;
        $arr1 = str_split($operand1, $splitLength);
        $arr2 = str_split($operand2, $splitLength);

        $result = [];
        $count = count($arr1);
        for ($count; $count > 0; $count--) {

            $key = $count - 1;
            $val1 = (int) $arr1[$key];
            $val2 = (int) $arr2[$key];
            $l = \strlen($val1);

            if ($addInt) {
                $val1++;
                $addInt = false;
            }


            $res = (string) ($val1 + $val2);
            if (\strlen($res) > $l) {
                $res = substr($res, 1);
                $addInt = true;
            }
            $result[] = $res;
        }

        $result = implode('', array_reverse($result));

        return [$result, $addInt];
    }


}