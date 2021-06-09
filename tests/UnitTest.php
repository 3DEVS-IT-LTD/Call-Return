<?php


namespace ThreeDevs\UnitTests;

use PHPUnit\Framework\TestCase;
use ThreeDevs\CallReturn\CallReturn;

class UnitTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
 
    }

    public function testErrorReturn()
    {
        $ret = new CallReturn();

        $ret->add_error('Err');

        self::assertTrue($ret->is_error());
    }

    public function testErrorSuccess()
    {
        $ret = new CallReturn();

        //should be a success from beginning
        self::assertTrue($ret->is_success());

        $ret->add_success(1, 'done');

        self::assertTrue($ret->is_success());
    }

    public static function tearDownAfterClass(): void
    {

    }
}