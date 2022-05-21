<?php
/**
 * Example Project (http://www.siworks.com)
 *
 * Created by Rafael N. Garbinatto (rafael@siworks.com)
 * date 25/03/2017
 * @package Siworks\Slim\Doctrine\Entity\Example\Entry
 * @copyright SIWorks
 */

namespace Siworks\Slim\Doctrine\Example\Entity\Contract;

use Doctrine\ORM\Mapping as ORM;
use \Siworks\Slim\Doctrine\Entity\AbstractEntity as AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="Siworks\Slim\Doctrine\Example\Repository\ContractRepository")
 * @ORM\Table(name="contract", indexes={
 *     @ORM\Index(name="idx_start", columns={"start_dt"}),
 *     @ORM\Index(name="idx_end", columns={"end_dt"}),
 *     @ORM\Index(name="idx_canceled", columns={"canceled_at"}),
 *     @ORM\Index(name="idx_client", columns={"client_id"}),
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Contract extends AbstractEntity
{
    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @ORM\Column(type="guid",nullable=true)
     */
    protected $ext_productId;

    /**
     * @ORM\Column(type="integer", length=3, nullable=true)
     */
    protected $periodicity;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $start_dt;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    protected $end_dt;

    /**
     * @ORM\Column( type="date", nullable=true)
     */
    protected $canceled_at;

    /**
     * @ORM\Column(type="string")
     */
    protected $client_id;

    /**
     * @ORM\ManyToOne(fetch="EAGER", targetEntity="Siworks\Slim\Doctrine\Example\Entity\Account\Account", inversedBy="contracts")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     */
    protected $account;

}
