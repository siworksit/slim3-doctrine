<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 10/8/17
 * Time: 7:32 PM
 */

namespace Unit\Doctrine\Traits\Helpers;

use Siworks\Slim\Doctrine\Traits\Helpers\ObjectHelpers;
use PHPUnit\Framework\TestCase;

class ObjectHelpersTest extends TestCase
{
    /*
    * @var Siworks\Slim\Doctrine\Traits\Helpers\ObjectHelpers
    */
    public $objectHelpers;

    public function setUp()
    {
        /*
         * @var Siworks\Slim\Doctrine\Traits\Helpers\ObjectHelpers
         */
        $this->objectHelpers = $this->getMockForTrait('Siworks\Slim\Doctrine\Traits\Helpers\ObjectHelpers');
    }

    public function testToArrayObj()
    {
        $obj = new class {
            public $var1 = 'test1';
            public $var2 = 'test2';
            public $var3 = 'test3';
            public $var4 = 'test4';
            public $var5 = 'test5';
        };
        $assertArray = [
            'var1' => 'test1',
            'var2' => 'test2',
            'var3' => 'test3',
            'var4' => 'test4',
            'var5' => 'test5',
        ];
        $this->assertEquals($assertArray,$this->objectHelpers->toArray($obj));
    }

    public function testToArrayObjFilter()
    {
        $obj = new class {
            public $var1 = 'test1';
            public $var2 = 'test2';
            public $var3 = 'test3';
            public $var4 = 'test4';
            public $var5 = 'test5';
        };
        $assertArray = [
            'var1' => 'test1',
            'var2' => 'test2',
            'var4' => 'test4',
        ];

        $filterKeys = [
            'var3',
            'var5',
        ];
        $this->assertEquals($assertArray,$this->objectHelpers->toArray($obj,$filterKeys));
    }

    public function testToArrayObjDate()
    {
        $obj = new class {
            public $var1;
            public $var2 = 'test2';
            public $var3 = 'test3';
            public $var4 = 'test4';
            public $var5 = 'test5';

            public function __construct()
            {
                $this->var1 = new \DateTime("2017-01-01 00:00:00");
            }
        };
        $assertArray = [
            'var1' => '2017-01-01 00:00:00',
            'var2' => 'test2',
            'var3' => 'test3',
            'var4' => 'test4',
            'var5' => 'test5',
        ];
        $this->assertEquals($assertArray,$this->objectHelpers->toArray($obj));
    }
}
