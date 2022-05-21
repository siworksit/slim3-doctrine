<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 10/8/17
 * Time: 1:50 PM
 */

namespace Unit\Doctrine\Controller;

use Siworks\Slim\Doctrine\Controller\AbstractController;
use Siworks\Slim\Tests\Unit\BaseTestCase;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

class AbstractControllerTest extends BaseTestCase
{
    /**
     * @var \Siworks\Slim\Doctrine\Controller\AbstractController
     */
    protected $controller;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    public function setUp()
    {
        $this->controller = $this->getMockBuilder('Siworks\Slim\Doctrine\Controller\AbstractController')
            ->setMockClassName('AbstractController')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $returnJson = $this->getMockBuilder("json")
            ->setMethods(['extractObject'])
            ->getMock();
        $returnJson->method('extractObject')
            ->will($this->returnValue(['retorno1','retorno2','retorno3']));

        $modelMock = $this->getMockBuilder('Siworks\Slim\Doctrine\Model\AbstractModel')
            ->setMockClassName('AbstractModel')
            ->disableOriginalConstructor()
            ->getMock();
        $modelMock->method('create')->will($this->returnValue($returnJson));
        $modelMock->method('update')->will($this->returnValue($returnJson));
        $modelMock->method('remove')->will($this->returnValue($returnJson));
        $modelMock->method('findAll')->will($this->returnValue(['retorno1','retorno2','retorno3']));

        $environment = Environment::mock(
            [
                'REQUEST_URI' => '/'
            ]
        );

        // Set up a request object based on the environment
        $this->request = Request::createFromEnvironment($environment);

        // Set up a response object
        $this->response = new Response();

        $this->controller->setModelEntity($modelMock);
        $this->controller->setEntityManager($this->getEntityManagerMock());
    }

    public function testGetEntityManager()
    {
        $this->assertInstanceOf('\Doctrine\ORM\EntityManagerInterface', $this->controller->getEntityManager());
    }

    public function testGetModel()
    {
        $this->assertInstanceOf('\Siworks\Slim\Doctrine\Model\IModel', $this->controller->getModelEntity());
    }

    public function testCreateAction()
    {
        $this->request = $this->request->withHeader('Content-Type', 'application/json');
        $this->request = $this->request->withMethod('POST');
        $this->request = $this->request->withParsedBody(["retorno1","retorno2","retorno3"]);

        $response = $this->controller->createAction($this->request,$this->response,[]);
        $this->assertEquals('["retorno1","retorno2","retorno3"]',$response->getBody()->__toString());
    }

    public function testUpdateAction()
    {
        $this->request = $this->request->withHeader('Content-Type', 'application/json');
        $this->request = $this->request->withMethod('POST');
        $this->request = $this->request->withParsedBody(["retorno1","retorno2","retorno3"]);

        $response = $this->controller->updateAction($this->request,$this->response,[]);
        $this->assertEquals('["retorno1","retorno2","retorno3"]',$response->getBody()->__toString());
    }

    public function testRemoveAction()
    {
        $response = $this->controller->removeAction($this->request,$this->response,[]);
        $this->assertEquals('["retorno1","retorno2","retorno3"]',$response->getBody()->__toString());
    }

    public function testFetchAllAction()
    {
        $response = $this->controller->fetchAllAction($this->request,$this->response,[]);
        $this->assertEquals('["retorno1","retorno2","retorno3"]',$response->getBody()->__toString());
    }

    public function testFetchValidate()
    {
        $this->assertEquals(["retorno1","retorno2","retorno3"],$this->controller->fetchValidate(["retorno1","retorno2","retorno3"]));
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Attribute 'filters' is required or is not array
     */
    public function testFetchValidateExpFilter()
    {
        $filterError = ['filters' => 'teste'];
        $this->controller->fetchValidate($filterError);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage value 'orders' is invalid required [asc, desc] (ABMD00035exc)
     */
    public function testFetchValidateExpOrder()
    {
        $filterError = ['order' => ['asdad' => 123]];
        $this->controller->fetchValidate($filterError);
    }

}
