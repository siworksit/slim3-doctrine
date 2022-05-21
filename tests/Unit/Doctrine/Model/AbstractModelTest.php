<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 11/10/17
 * Time: 20:15 PM
 */

namespace Unit\Doctrine\Model;

use Siworks\Slim\Doctrine\Model\AbstractModel;
use Siworks\Slim\Tests\Unit\BaseTestCase;
use Siworks\Slim\Doctrine\Repository;
use Symfony\Component\Config\Definition\Exception\Exception;

class AbstractModelTest extends BaseTestCase
{
    /**
     * @var \Siworks\Slim\Doctrine\Model\AbstractModel
     */
    protected $model;

    public function setUp()
    {
        $this->model = $this->getMockBuilder('Siworks\Slim\Doctrine\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->setMethods(array('getObj','populateAssociation','populateObject'))
            ->getMockForAbstractClass();
        $this->model->setEntityManager($this->getEntityManagerMock());
        $this->model->setEntityName($this->getEntityName());

        $this->model->expects($this->any())
            ->method('getObj')
            ->willReturn($this->getEntityMock());
        $this->model->expects($this->any())
            ->method('populateAssociation')
            ->with($this->getEntityMock())
            ->willReturn($this->getEntityMock());

        $this->model->expects($this->any())
            ->method('populateObject')
            ->with($this->getEntityMock())
            ->willReturn($this->getEntityMock());

        $this->model->setRepository($this->getRepositoryMock());
    }

    public function testGetEntityManager()
    {
        $this->assertInstanceOf('\Doctrine\ORM\EntityManagerInterface', $this->model->getEntityManager());
    }

    public function testSetEntityManager()
    {
        $this->model->setEntityManager($this->getEntityManagerMock());
        $this->assertInstanceOf('\Doctrine\ORM\EntityManagerInterface', $this->model->getEntityManager());
    }

    public function testSetNameEntity()
    {
        $this->assertEquals($this->getEntityName(),$this->model->getEntityName());
    }

    public function testGetNameEntity()
    {
        $this->model->setEntityName('Potato');
        $this->assertEquals('Potato',$this->model->getEntityName());
    }

    public function testCreate()
    {
        $this->assertEquals($this->getEntityMock(),$this->model->create(['12345' => '12345']));
    }

    public function testGetObj()
    {
        $stub = $this->getMockBuilder('Siworks\Slim\Doctrine\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $stub->setEntityName('Exception');
        $this->assertInstanceOf(\Exception::class, $stub->getObj());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Argument 'Id' value is not set or is invalid (ACCENT013exc)
     */
    public function testUpdateIdEmpty()
    {
        $this->model->update([]);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Argument 'Id' value is not set or is invalid (ACCENT013exc)
     */
    public function testUpdateIdFormat()
    {
        $this->model->update(['id' => 'aaaaaaaa']);
    }

    public function testUpdateIdUuid()
    {
        $this->assertEquals($this->getEntityMock(),$this->model->update(['id' => 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa']));
    }

    public function testUpdateIdNumeric()
    {
        $this->assertEquals($this->getEntityMock(),$this->model->update(['id' => '12345']));
    }

    public function testUpdateTypeObj()
    {
        $this->model->setEntityName('Exception');
        $this->assertNull($this->model->update(['id' => '12345']));
    }

    public function testRemove()
    {
        $this->assertEquals($this->getEntityMock(),$this->model->remove(['id' => '12345']));
    }

    public function testFindAll()
    {
        $this->assertEquals([['id' => 12345, 'name' => 'Diego', 'last' => 'first','created_at' => null,'updated_at' => null]],$this->model->findAll(['id' => '12345']));
    }


    public function testPopulateObject()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $stub->setEntityName($this->getEntityName());
        $this->assertEquals($this->getEntityMock()->toArray(),$stub->populateObject($this->getEntityMock())->toArray());
    }

    public function testPopulateAssociation()
    {
        $stub = $this->getMockBuilder('Siworks\Slim\Doctrine\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
       $stub->setEntityManager($this->getEntityManagerMock());
       $stub->setEntityName($this->getEntityName());

       $stub->setRepository($this->getRepositoryMock());

        $stub->setData($this->getEntityMock()->toArray());

        $method = new \ReflectionMethod(get_class($stub), 'populateAssociation');
        $method->setAccessible(true);
        $this->assertEquals($this->getEntityMock(),$method->invokeArgs($stub,[$this->getEntityMock()]));
    }

    public function testExtractObject()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $stub->setEntityName($this->getEntityName());
        $this->assertEquals(['created_at' => null, 'updated_at' => null],$stub->extractObject($this->getEntityMock()));
    }


}
