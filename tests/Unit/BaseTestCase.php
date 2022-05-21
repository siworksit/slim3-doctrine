<?php

namespace Siworks\Slim\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends TestCase
{

    protected $entityName = 'Siworks\Slim\Doctrine\Entity\AbstractEntity';

    /**
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param mixed $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }
    /**
     * @return \Doctrine\ORM\EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getEntityManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'getConnection',
                    'getClassMetadata',
                    'getRepository',
                    'find',
                    'persist',
                    'remove',
                    'flush',
                    'close',
                )
            )
            ->getMock();
        $mock->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($this->getConnectionMock()));
        $mock->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($this->getClassMetadataMock()));
        $mock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->getRepositoryMock()));
        $mock->expects($this->any())
            ->method('find')
            ->with($this->isType('string'),$this->isType('integer'))
            ->will($this->returnValue($this->getEntityMock()));
        $mock->expects($this->any())
            ->method('persist')
            ->with($this->getEntityMock())
            ->will($this->returnValue($this->getEntityMock()));
        $mock->expects($this->any())
            ->method('remove')
            ->with($this->getEntityMock())
            ->will($this->returnValue($this->getEntityMock()));
        $mock->expects($this->any())
            ->method('flush')
            ->with($this->getEntityMock())
            ->will($this->returnValue($this->getEntityMock()));
        return $mock;
    }

    /**
     * @return \Doctrine\Common\Persistence\Mapping\ClassMetadata|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getClassMetadataMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(array('getTableName'))
            ->getMock();
        $mock->expects($this->any())
            ->method('getTableName')
            ->will($this->returnValue('{tableName}'));
        $mock->name = $this->getEntityName();
        return $mock;
    }

    /**
     * @return \Doctrine\DBAL\Platforms\AbstractPlatform|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getDatabasePlatformMock()
    {
        $mock = $this->getAbstractMock(
            'Doctrine\DBAL\Platforms\AbstractPlatform',
            array(
                'getName',
                'getTruncateTableSQL',
            )
        );
        $mock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('mysql'));
        $mock->expects($this->any())
            ->method('getTruncateTableSQL')
            ->with($this->anything())
            ->will($this->returnValue('#TRUNCATE {table}'));
        return $mock;
    }
    /**
     * @return \Doctrine\DBAL\Connection|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getConnectionMock()
    {
        $mock = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'beginTransaction',
                    'commit',
                    'rollback',
                    'prepare',
                    'query',
                    'executeQuery',
                    'executeUpdate',
                    'getDatabasePlatform',
                )
            )
            ->getMock();
        $mock->expects($this->any())
            ->method('prepare')
            ->will($this->returnValue($this->getStatementMock()));
        $mock->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->getStatementMock()));
        $mock->expects($this->any())
            ->method('getDatabasePlatform')
            ->will($this->returnValue($this->getDatabasePlatformMock()));
        return $mock;
    }
    /**
     * @return \Doctrine\DBAL\Driver\Statement|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getStatementMock()
    {
        $mock = $this->getAbstractMock(
            'Doctrine\DBAL\Driver\Statement', // In case you run PHPUnit <= 3.7, use 'Mocks\DoctrineDbalStatementInterface' instead.
            array(
                'bindValue',
                'execute',
                'rowCount',
                'fetchColumn',
            )
        );
        $mock->expects($this->any())
            ->method('fetchColumn')
            ->will($this->returnValue(1));
        return $mock;
    }
    /**
     * @param string $class   The class name
     * @param array  $methods The available methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAbstractMock($class, array $methods)
    {
        return $this->getMockForAbstractClass(
            $class,
            array(),
            '',
            true,
            true,
            true,
            $methods,
            false
        );
    }

    public function getEntityMock()
    {
        $stubEntity = $this->getMockBuilder($this->getEntityName())
            ->getMockForAbstractClass();
        $stubEntity->id = 12345;
        $stubEntity->name = 'Diego';
        $stubEntity->last = 'first';
        return $stubEntity;
    }


    public function getRepositoryMock()
    {
        $mock = $this->getMockBuilder('\Siworks\Slim\Doctrine\Repository\AbstractRepository')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'findOneById',
                    'findBy',
                    'remove',
                    'getSimpleListBy',
                    'save'
                ))->getMockForAbstractClass();

        $mock->expects($this->any())
            ->method('findOneById')
            ->willReturn($this->getEntityMock());
        $mock->expects($this->any())
            ->method('findBy')
            ->willReturn([$this->getEntityMock()]);
        $mock->expects($this->any())
            ->method('remove')
            ->willReturn($this->getEntityMock());
        $mock->expects($this->any())
            ->method('getSimpleListBy')
            ->willReturn([$this->getEntityMock()]);
        $mock->expects($this->any())
            ->method('save')
            ->willReturn($this->getEntityMock());
        return $mock;
    }
}
