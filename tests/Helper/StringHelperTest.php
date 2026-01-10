<?php

declare(strict_types=1);

namespace Test\Helper;

use Cheremhovo1990\Framework\Helper\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testEqualEnd()
    {
        $this->assertTrue(StringHelper::equalEnd('Nurse look after patients in hospitals', 'hospitals'));
        $this->assertFalse(StringHelper::equalEnd('He drives a bus', 'hospitals'));
    }

    public function testReplace()
    {
        $this->assertEquals('I am driving to work', StringHelper::replace('trying', 'driving', 'I am trying to work'));
        $this->assertEquals('I am trying to work', StringHelper::replace('having', 'driving', 'I am trying to work'));
    }

    public function testReplaceStart()
    {
        $this->assertEquals('She is driving to work', StringHelper::replaceStart('He', 'She', 'He is driving to work'));
        $this->assertEquals('He is driving to work', StringHelper::replaceStart('It', 'She', 'He is driving to work'));
    }

    public function testReplaceEnd()
    {
        $this->assertEquals('He is driving at home', StringHelper::replaceEnd('to work', 'at home', 'He is driving to work'));
        $this->assertEquals('He is driving to work', StringHelper::replaceEnd('at work', 'at home', 'He is driving to work'));
    }

    public function testCamelCaseToId()
    {
        $this->assertEquals('main-about', StringHelper::camelCaseToId('MainAbout'));
        $this->assertEquals('main-about', StringHelper::camelCaseToId('main-about'));
        $this->assertEquals('main', StringHelper::camelCaseToId('Main'));
    }
}