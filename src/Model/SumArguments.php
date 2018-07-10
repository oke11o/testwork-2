<?php

namespace App\Model;

class SumArguments
{
    /**
     * @var string
     */
    private $operand1;
    /**
     * @var string
     */
    private $operand2;

    /**
     * @var int
     */
    private $fractionalLength = 0;

    /**
     * SumArguments constructor.
     * @param string $operand1
     * @param string $operand2
     * @param int $fractionalLength
     */
    public function __construct(string $operand1, string $operand2, int $fractionalLength)
    {
        $this->operand1 = $operand1;
        $this->operand2 = $operand2;
        $this->fractionalLength = $fractionalLength;
    }

    /**
     * @return string
     */
    public function getOperand1(): string
    {
        return $this->operand1;
    }

    /**
     * @return string
     */
    public function getOperand2(): string
    {
        return $this->operand2;
    }

    /**
     * @return int
     */
    public function getFractionalLength(): int
    {
        return $this->fractionalLength;
    }
}