<?php

namespace Tests\App\Service;

use App\Service\BigNumberFormatter;
use App\Service\Calculator;
use App\Service\SumArgumentsFactory;

class CalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \App\Exception\CalculatorException
     */
    public function throwInvalidConstructor()
    {
        new Calculator('-');
    }

    public function initialSeparators()
    {
        $calculator = new Calculator('.');
        $this->assertEquals('.', $calculator->getFractionalSeparator());
        $this->assertEquals(',', $calculator->getAlterFractionalSeparator());
        $this->assertTrue(['.' => ','] === $calculator->getAlterSeparators());

        $calculator = new Calculator();
        $this->assertEquals(',', $calculator->getFractionalSeparator());
        $this->assertEquals('.', $calculator->getAlterFractionalSeparator());
        $this->assertTrue([',' => '.'] === $calculator->getAlterSeparators());
    }

    /**
     * @test
     * @dataProvider invalidArgumentExamples
     * @expectedException \App\Exception\CalculatorException
     */
    public function invalidArgument($a, $b)
    {
        $calculator = new Calculator(new BigNumberFormatter(), new SumArgumentsFactory());
        $calculator->sum($a, $b);
    }

    public function invalidArgumentExamples()
    {
        $one = [
            [
                'a' => '12321s',
                'b' => '1',
            ],
            [
                'a' => '12321.23s',
                'b' => '1',
            ],
            [
                'a' => '12321.12313.',
                'b' => '1',
            ],
            [
                'a' => '.12321.12313',
                'b' => '1',
            ],
        ];
        $result = $one;
        foreach ($one as $set) {
            $result[] = [
                'a' => $set['b'],
                'b' => $set['a'],
            ];
        }

        return $result;
    }

    /**
     * @test
     * @dataProvider examples
     */
    public function sum($a, $b, $sum)
    {
        $calculator = new Calculator(new BigNumberFormatter(), new SumArgumentsFactory());
        $this->assertEquals($sum, $calculator->sum($a, $b));
    }

    public function examples()
    {
        return [
            [
                'a' => '.0',
                'b' => '.5',
                'sum' => '0.5',
            ],
            [
                'a' =>    '7777777777777777777777777774777775.7777777777777777777777777777777777777777777777',
                'b' =>    '8888888888888888888888888884888885.8888888888888888888888888888888888888888888888',
                'sum' => '16666666666666666666666666659666661.6666666666666666666666666666666666666666666665',
            ],
            [
                'a' =>    '.7777777777777777777777777777777777777777',
                'b' =>    '.8888888888888888888888888888888888888888',
                'sum' => '1.6666666666666666666666666666666666666665',
            ],
            [
                'a' =>    '.7777777777777777777777777777777777777777',
                'b' =>    '.888888888888888888888888888888888888',
                'sum' => '1.6666666666666666666666666666666666657777',
            ],
            [
                'a' => '7777777777777777777777777777777777777777',
                'b' => '8888888888888888888888888888888888888888',
                'sum' => '16666666666666666666666666666666666666665',
            ],
            [
                'a' =>    '7777777777777777777777777777777777.7777777777777777777777777777777777777777777777',
                'b' =>    '8888888888888888888888888888888888.8888888888888888888888888888888888888888888888',
                'sum' => '16666666666666666666666666666666666.6666666666666666666666666666666666666666666665',
            ],
            [
                'a' => '3',
                'b' => '4',
                'sum' => '7',
            ],
            [
                'a' =>   '3333333333333333333333333333333333333333',
                'b' =>   '4444444444444444444444444444444444444444',
                'sum' => '7777777777777777777777777777777777777777',
            ],
            [
                'a' => '.0',
                'b' => '.0',
                'sum' => '0',
            ],
            [
                'a' => '.3',
                'b' => '.4',
                'sum' => '0.7',
            ],
            [
                'a' => '.5',
                'b' => '.5',
                'sum' => '1',
            ],
        ];
    }
}
