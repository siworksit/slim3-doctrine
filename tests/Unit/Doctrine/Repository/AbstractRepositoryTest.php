<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 13/10/17
 * Time: 5:40 PM
 */

namespace Unit\Doctrine\Repository;

use Siworks\Slim\Tests\Unit\BaseTestCase;
use MMoussa\Doctrine\Test\ORM\QueryBuilderMocker;

class AbstractRepositoryTest extends BaseTestCase
{
    /**
     * @var \Siworks\Slim\Doctrine\Repository\AbstractRepository
     */
    protected $repository;

    public function setUp()
    {
        $this->repository = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->repository->__construct($this->getEntityManagerMock(), $this->getClassMetadataMock());
    }

    public function testGetSingleById()
    {
        $this->assertEquals($this->getEntityMock(),$this->repository->getSingleById(5));
    }

    public function testGetSingleByIdNull()
    {
        $classMeta = $this->getClassMetadataMock();
        $classMeta->name = 'Error';
        $this->repository->__construct($this->getEntityManagerMock(), $classMeta);
        $this->assertNull($this->repository->getSingleById(5));
    }

    public function testGetSimpleListBy()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'checkAttrib'
                ))->getMockForAbstractClass();

        $stub->expects($this->any())
            ->method('checkAttrib')
            ->willReturn(true);
        $stub->__construct($this->getEntityManagerMock(), $this->getClassMetadataMock());
        $this->assertEquals([$this->getEntityMock()],$stub->getSimpleListBy());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage No columns specified for table Siworks\Slim\Doctrine\Entity\AbstractEntity(ABSREP00012exc)
     */
    public function testGetSimpleListByAttrib()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'checkAttrib'
                ))->getMockForAbstractClass();

        $stub->expects($this->any())
            ->method('checkAttrib')
            ->willReturn(false);
        $stub->__construct($this->getEntityManagerMock(), $this->getClassMetadataMock());
        $this->assertEquals([$this->getEntityMock()],$stub->getSimpleListBy());
    }

    public function testGetListBy()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'checkAttrib',
                    'createQueryBuilder',
                    'getPaginate'
                ))->getMockForAbstractClass();

        $stub->expects($this->any())
            ->method('checkAttrib')
            ->willReturn(true);
        $qbm = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->setMethods([
                'select',
                'from',
                'getQuery'
            ])
            ->getMock();
        $qbm->expects($this->any())
            ->method('select')
            ->willReturn($qbm);

        $qbm->expects($this->any())
            ->method('from')
            ->willReturn($qbm);


        $qbm->expects($this->any())
            ->method('getQuery')
            ->willReturn(new \stdClass());

        $pm = $this->getMockBuilder('Doctrine\ORM\Tools\Pagination\Paginator')
            ->disableOriginalConstructor()
            ->getMock();


        $stub->expects($this->any())
            ->method('getPaginate')
            ->with($this->anything(),$this->anything(),$this->anything())
            ->willReturn($pm);

        $stub->expects($this->any())
            ->method('createQueryBuilder')
            ->willReturn($qbm);

        $stub->__construct($this->getEntityManagerMock(), $this->getClassMetadataMock());

        $this->assertEquals($pm,$stub->getListBy([],1));
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid attribute filter (ACCREP exc001)
     */
    public function testGetListByAttrib()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'checkAttrib'
                ))->getMockForAbstractClass();

        $stub->expects($this->any())
            ->method('checkAttrib')
            ->willReturn(false);
        $stub->__construct($this->getEntityManagerMock(), $this->getClassMetadataMock());
        $this->assertEquals([$this->getEntityMock()],$stub->getListBy([],1));
    }


    /**
     * @expectedException        Symfony\Component\Debug\Exception\ClassNotFoundException
     * @expectedExceptionMessage Namespace aaaaa not found (ABSREP0011exc)
     */
    public function testCheckAttribError()
    {
        $meta = $this->getClassMetadataMock();
        $meta->name = 'aaaaa';
        $this->repository->__construct($this->getEntityManagerMock(), $meta);
        $this->repository->checkAttrib([]);
    }

    public function testCheckAttrib()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'getAttributesList'
                ))->getMockForAbstractClass();

        $stub->expects($this->any())
            ->method('getAttributesList')
            ->willReturn(['created_at'=> 5,'updated_at' => 'Diego']);
        $stub->__construct($this->getEntityManagerMock(), $this->getClassMetadataMock());
        $this->assertTrue($stub->checkAttrib([]));
    }

    public function testCheckAttribFalse()
    {
        $stub = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'getAttributesList'
                ))->getMockForAbstractClass();

        $stub->expects($this->any())
            ->method('getAttributesList')
            ->willReturn(['blablabla'=> 5,'updated_at' => 'Diego']);
        $stub->__construct($this->getEntityManagerMock(), $this->getClassMetadataMock());
        $this->assertFalse($stub->checkAttrib([]));
    }

    public function testSave()
    {
        $this->assertEquals($this->getEntityMock(),$this->repository->save($this->getEntityMock()));
    }

    public function testRemove()
    {
        $this->assertEquals($this->getEntityMock(),$this->repository->remove($this->getEntityMock()));
    }
}