<?php
/**
 * Created by PhpStorm.
 * User: ng
 * Date: 23/06/17
 * Time: 20:31
 */

namespace Siworks\Slim\Doctrine\Example\Model;

use Siworks\Slim\Doctrine\Model\AbstractModel;
use Doctrine\ORM\EntityManager;

class ContractModel extends AbstractModel
{

    public function __construct( EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository('Siworks\Slim\Doctrine\Example\Entity\Contract\Contract');
        parent::__construct($entityManager);
    }
}