<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 10/8/17
 * Time: 7:03 PM
 */

namespace Unit\Doctrine\Traits\Entity;

use Siworks\Slim\Doctrine\Traits\Entity\TimeStampable;
use PHPUnit\Framework\TestCase;

class TimeStampableTest extends TestCase
{

    /*
     * @var Siworks\Slim\Doctrine\Traits\Entity\TimeStampable
     */
    public $timeStampable;

    public function setUp()
    {
        /*
         * @var Siworks\Slim\Doctrine\Traits\Entity\TimeStampable
         */
        $this->timeStampable = $this->getMockForTrait('Siworks\Slim\Doctrine\Traits\Entity\TimeStampable');
    }

    public function testPrePersistListener()
    {
        $this->timeStampable->prePersistListener();
        $this->assertInstanceOf("DateTimeInterface", $this->timeStampable->getCreated());
        $this->assertInstanceOf("DateTimeInterface", $this->timeStampable->getUpdated());
    }

    public function testSetCreated()
    {
        $this->timeStampable->setCreated(new \DateTime("NOW"));
        $this->assertInstanceOf("DateTimeInterface", $this->timeStampable->getCreated());
    }

    public function testSetUpdated()
    {
        $this->timeStampable->setUpdated(new \DateTime("NOW"));
        $this->assertInstanceOf("DateTimeInterface", $this->timeStampable->getUpdated());
    }

    public function testPreUpdateListener()
    {
        $this->timeStampable->preUpdateListener();
        $this->assertInstanceOf("DateTimeInterface", $this->timeStampable->getUpdated());
    }

}
