<?php

namespace App\Model;

use App\Exception\CalculatorException;

class BigNumber
{
    public const INTEGER_PART = 0;
    public const FRACTIONAL_PART = 1;

    const FRACTIONAL_SEPARATOR = '.';
    /**
     * @var string
     */
    private $integer;
    /**
     * @var string
     */
    private $fractional;

    /**
     * BigNumber constructor.
     * @param string $integer
     * @param string $fractional
     * @throws CalculatorException
     */
    public function __construct(string $integer = '0', string $fractional = '0')
    {
        self::assertArgument($integer);
        self::assertArgument($fractional);

        $this->integer = $this->normalizePart($integer, self::INTEGER_PART);
        $this->fractional = $this->normalizePart($fractional, self::FRACTIONAL_PART);
    }

    /**
     * @return string
     */
    public function getInteger(): string
    {
        return $this->integer;
    }

    /**
     * @return string
     */
    public function getFractional(): string
    {
        return $this->fractional;
    }

    /**
     * @param BigNumber $operand
     * @return int -1,0,1
     */
    public function compare(BigNumber $operand): int
    {
        $thisIntLen = \strlen($this->getInteger());
        $operandIntLen = \strlen($operand->getInteger());

        if ($thisIntLen !== $operandIntLen) {
            return $thisIntLen > $operandIntLen ? 1 : -1;
        }

        if ($this->getInteger() !== $operand->getInteger()) {
            return $this->getInteger() > $operand->getInteger() ? 1 : -1;
        }

        if ($this->getFractional() === $operand->getFractional()) {
            return 0;
        }

        return $this->getFractional() > $operand->getFractional() ? 1: -1;
    }

    /**
     * @param string $operand
     * @return BigNumber
     * @throws CalculatorException
     */
    public static function fromString(string $operand): BigNumber
    {
        self::assertArgument($operand);

        $result = explode(self::FRACTIONAL_SEPARATOR, $operand);
        $integer = array_shift($result);
        $fractional = array_shift($result);

        if (!$integer) {
            $integer = '0';
        }
        if (!$fractional) {
            $fractional = '0';
        }

        return new static($integer, $fractional);
    }

    /**
     * @param string $operand
     * @param int $part
     * @return string
     * @throws CalculatorException
     */
    private function normalizePart(string $operand, int $part = self::INTEGER_PART): string
    {
        if (self::INTEGER_PART === $part) {
            $operand = ltrim($operand, '0');
        } elseif (self::FRACTIONAL_PART === $part) {
            $operand = rtrim($operand, '0');
        } else {
            throw new CalculatorException(sprintf('Invalid part type "%s"', $part));
        }

        if ('' === $operand) {
            $operand = '0';
        }

        return $operand;
    }

    /**
     * @param $operand
     * @throws CalculatorException
     */
    private static function assertArgument($operand): void
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
}