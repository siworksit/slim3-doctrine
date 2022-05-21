<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 12/10/17
 * Time: 19:25 PM
 */

namespace Unit\Doctrine\Entity;

use Siworks\Slim\Doctrine\Entity\AbstractEntity;
use PHPUnit\Framework\TestCase;

class AbstractEntityTest extends TestCase
{
    /**
     * @var \Siworks\Slim\Doctrine\Controller\AbstractEntity
     */
    protected $entity;

    public function setUp()
    {
        $this->entity = $this->getMockBuilder('Siworks\Slim\Doctrine\Entity\AbstractEntity')
            ->getMockForAbstractClass();
        $this->entity->potato = 25;
    }

    public function testGetCall()
    {
        $this->assertEquals(25,$this->entity->getPotato());
    }

    public function testSetCall()
    {
        $this->entity->setPotato(78);
        $this->assertEquals(78,$this->entity->getPotato());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Attribute does not exist. (ABSENT0001exc)
     */
    public function testAttributeNotExists()
    {
        $this->entity->getTomato(78);

    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage The method is not defined. (ABSENT0002exc)
     */
    public function testMethodNotExists()
    {
        $this->entity->batPotato(78);

    }
}
