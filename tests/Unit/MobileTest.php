<?php

namespace Tests\Unit;

use App\Rules\Mobile;
use PHPUnit\Framework\TestCase;

class MobileTest extends TestCase
{
    /**
     * Local numbers
     *
     * @return void
     */
    public function testExample1()
    {
        $mobileRule = new Mobile();

        $this->assertTrue($mobileRule->passes('test', '017312345678'));
        $this->assertTrue($mobileRule->passes('test', '0173 123 456 78'));

        $this->assertFalse($mobileRule->passes('test', '01805828325'));
        $this->assertFalse($mobileRule->passes('test', '09001 0 66 11 3'));
    }

    /**
     * German numbers
     *
     * @return void
     */
    public function testExample2()
    {
        $mobileRule = new Mobile();

        $this->assertTrue($mobileRule->passes('test', '004917312345678'));
        $this->assertTrue($mobileRule->passes('test', '0049 173 12 345 678'));
        $this->assertTrue($mobileRule->passes('test', '+4917312345678'));
        $this->assertTrue($mobileRule->passes('test', '+49 173 123 456 78'));

        $this->assertFalse($mobileRule->passes('test', '+49 18 05 36 01 24'));
    }

    /**
     * International numbers
     *
     * @return void
     */
    public function testExample3()
    {
        $mobileRule = new Mobile();

        $this->assertTrue($mobileRule->passes('test', '+380 73 0905901'));
        $this->assertTrue($mobileRule->passes('test', '+380730905901'));
        $this->assertTrue($mobileRule->passes('test', '00380-73-0905901'));
        $this->assertTrue($mobileRule->passes('test', '00380730905901'));

        $this->assertFalse($mobileRule->passes('test', '+357 26422360'));
    }

    /**
     * International numbers
     *
     * @return void
     */
    public function testExample4()
    {
        $mobileRule = new Mobile();

        $this->assertFalse($mobileRule->passes('test', 'abc'));
        $this->assertFalse($mobileRule->passes('test', '0.5'));
    }
}
