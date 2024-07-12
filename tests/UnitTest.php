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

    public function testKeyValue()
    {
        $ret = new CallReturn();

        $ret->add_data(['asd' => 'ONE']);

        $ret->setKeyValueData('total', '100');
        $ret->setKeyValueData('status', 'error');

        $array = $ret->get_in_array();

        self::assertTrue(
            isset($array['status_code'])
            && isset($array['status']) && $array['status'] == 'success'
            && isset($array['error'])
            && isset($array['error_code'])
            && isset($array['success'])
            && isset($array['success_code'])
            && isset($array['data'])
            && isset($array['total']) && $array['total'] == '100'
        );
    }

    public static function tearDownAfterClass(): void
    {

    }
}