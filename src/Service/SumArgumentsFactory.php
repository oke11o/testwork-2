<?php

namespace App\Service;

use App\Model\BigNumber;
use App\Model\SumArguments;

class SumArgumentsFactory
{
    public function create(BigNumber $operand1, BigNumber $operand2)
    {
        $operand1Integer = $operand1->getInteger();
        $operand2Integer = $operand2->getInteger();
        $strlen1Integer = \strlen($operand1Integer);
        $strlen2Integer = \strlen($operand2Integer);
        if ($strlen1Integer !== $strlen2Integer) {
            if ($strlen1Integer > $strlen2Integer) {
                $operand2Integer = \str_pad($operand2Integer, $strlen1Integer, '0', STR_PAD_LEFT);
            } else {
                $operand1Integer = \str_pad($operand1Integer, $strlen2Integer, '0', STR_PAD_LEFT);
            }
        }

        $operand1Fractional = $operand1->getFractional();
        $operand2Fractional = $operand2->getFractional();
        $fractionalLength = \strlen($operand1Fractional);
        $strlen2 = \strlen($operand2Fractional);
        if ($fractionalLength !== $strlen2) {
            if ($fractionalLength > $strlen2) {
                $operand2Fractional = \str_pad($operand2Fractional, $fractionalLength, '0', STR_PAD_RIGHT);
            } else {
                $fractionalLength = $strlen2;
                $operand1Fractional = \str_pad($operand1Fractional, $fractionalLength, '0', STR_PAD_RIGHT);
            }
        }

        return new SumArguments(
            $operand1Integer.$operand1Fractional,
            $operand2Integer.$operand2Fractional,
            $fractionalLength
        );
    }
}