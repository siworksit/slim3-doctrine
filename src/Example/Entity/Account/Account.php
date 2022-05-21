<?php
/**
 * Example Project (http://www.siworks.com)
 *
 * Created by Rafael N. Garbinatto (rafael@siworks.com)
 * date 25/03/2017
 *
 * @copyright SIWorks
 */
namespace Siworks\Slim\Example\Entity\Account;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid as Uuid;
use \Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

use \Siworks\Slim\Doctrine\Entity\AbstractEntity as AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="Siworks\Slim\Doctrine\Example\Repository\AccountRepository")
 * @ORM\Table(name="account")
 * @ORM\HasLifecycleCallbacks()
 */
class Account extends AbstractEntity
{

    /**
     * all status possibilities for this system
     * Const ARRAY
     */
    const ACCOUNT_STATUS = array('active', 'inactive');

    /**
     * all currencies possibilities for this system
     * Const ARRAY
     */
    const CURRENCIES = array('real', 'dollar');

    /**
     * all payments day possibilities for this system
     * Const ARRAY
     */
    const PAYMENT_DAYS = array( 5, 10, 15, 20, 25, 30);

    /**
     * @var id integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(name="token", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $token;

    /**
     * @ORM\Column(name="display_name", type="string", length=30, nullable=true)
     */
    protected $displayName;

    /**
     * currency
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    protected $currency = 'real';

    /**
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    protected $status = "active";

    /**
     * @var \Integer
     * @ORM\Column(name="payment_day", type="integer", length=2)
     */
    protected $paymentDay;

    /**
     * @ORM\OneToMany(targetEntity="Siworks\Slim\Doctrine\Example\Entity\Contract\Contract", mappedBy="account")
     */
    protected $contracts;


    public function __construct()
    {
        $this->contracts = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function validate()
    {
        try{

            if ( ! Uuid::isValid($this->getToken()) )
            {
                throw new \InvalidArgumentException("'Token' value '{$this->getToken()}' is invalid (ACCENT0010exc)");
            }

            if ( ! in_array(strtolower($this->getStatus()), self::ACCOUNT_STATUS) )
            {
                throw new \InvalidArgumentException("Status value '{$this->getStatus()}' is invalid (ACCENT0011exc)");
            }

            if ( ! in_array($this->getPaymentDay(), self::PAYMENT_DAYS) )
            {
                throw new \InvalidArgumentException("PaymentDay value '{$this->getPaymentDay()}' is invalid (ACCENT0012exc)");
            }

            if ( ! in_array(strtolower($this->getCurrency()), self::CURRENCIES) )
            {
                throw new \InvalidArgumentException("Currency value '{$this->getCurrency()}' is invalid (ACCENT0013exc)");
            }
        }catch (\Exception $e){
            throw $e;
        }
    }

}