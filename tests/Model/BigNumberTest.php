<?php

namespace Tests\App\Model;

use App\Model\BigNumber;
use PHPUnit\Framework\TestCase;

class BigNumberTest extends TestCase
{
    /**
     * @test
     * @dataProvider compareExamples
     */
    public function compare($orig, $compared, $result)
    {
        $origEx = explode('.', $orig);
        $origNumber = new BigNumber($origEx[0], $origEx[1]);
        $comparedEx = explode('.', $compared);
        $comparedNumber = new BigNumber($comparedEx[0], $comparedEx[1]);

        $this->assertEquals($result, $origNumber->compare($comparedNumber));
    }

    public function compareExamples()
    {
        return [
            [
                '000123.123',
                '123.123000',
                0
            ],
            [
                '12300.123',
                '123.111',
                1
            ],
            [
                '123.123',
                '111.111',
                1
            ],
            [
                '123.123',
                '123.111',
                1
            ],
            [
                '111.111',
                '123.123',
                -1
            ],
        ];
    }
}
