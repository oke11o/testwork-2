<?php

namespace App\Service;

use App\Exception\CalculatorException;
use App\Model\BigNumber;
use App\Model\SumArguments;

class Calculator
{
    const INTEGER_PART = 'INTEGER_PART';
    const FRACTIONAL_PART = 'FRACTIONAL_PART';
    /**
     * @var BigNumberFormatter
     */
    private $formatter;
    /**
     * @var SumArgumentsFactory
     */
    private $sumArgumentsFactory;

    /**
     * Calculator constructor.
     * @param BigNumberFormatter $formatter
     * @param SumArgumentsFactory $sumArgumentsFactory
     */
    public function __construct(BigNumberFormatter $formatter, SumArgumentsFactory $sumArgumentsFactory)
    {
        $this->formatter = $formatter;
        $this->sumArgumentsFactory = $sumArgumentsFactory;
    }

    /**
     * @param string $base
     * @param string $add
     * @return string
     * @throws CalculatorException
     */
    public function sum(string $base, string $add): string
    {
        $arguments = $this->createSumArguments($base, $add);
        $result = $this->sumArgument($arguments);

        return $this->formatter->format($result);
    }

    /**
     * @param $base
     * @param $add
     * @return SumArguments
     * @throws CalculatorException
     */
    private function createSumArguments($base, $add): SumArguments
    {
        return $this->sumArgumentsFactory->create(BigNumber::fromString($base), BigNumber::fromString($add));
    }

    /**
     * @param SumArguments $arguments
     * @return BigNumber
     * @throws CalculatorException
     */
    private function sumArgument(SumArguments $arguments): BigNumber
    {
        [$result, $dig] = $this->sumInteger($arguments->getOperand1(), $arguments->getOperand2());
        if (!$dig && '0' === $result) {
            return new BigNumber();
        }

        if ($dig) {
            $result = '1'.$result;
        }

        $start = 0 - $arguments->getFractionalLength();
        $int = substr($result, 0, $start);
        if (!$int) {
            $int = '0';
        }
        $fra = substr($result, $start);

        return new BigNumber($int, $fra);
    }


    /**
     * @param string $operand1
     * @param string $operand2
     * @param bool $addInt
     * @return array
     */
    private function sumInteger(string $operand1, string $operand2, $addInt = false): array
    {
        if (0 === (int)$operand1 && 0 === (int)$operand2) {
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
            $val1 = (int)$arr1[$key];
            $val2 = (int)$arr2[$key];
            $l = \strlen($val1);

            if ($addInt) {
                $val1++;
                $addInt = false;
            }

            $res = (string)($val1 + $val2);
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