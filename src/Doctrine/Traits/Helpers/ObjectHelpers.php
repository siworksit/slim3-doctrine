<?php
/**
 * Created by PhpStorm.
 * User: ng
 * Date: 27/06/17
 * Time: 20:50
 */

namespace Siworks\Slim\Doctrine\Traits\Helpers;

use GeneratedHydrator\Configuration;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

trait ObjectHelpers
{
    /**
     * Recursively converts an Object to an Array
     *
     * @return array
     */
    public function toArray( $filterKeys = array(), $obj = null, $level = 1, $position = 0)
    {
        $filters_default =  array('__cloner__', '__isInitialized__', '__initializer__');
        $filterKeys = array_merge ($filterKeys, $filters_default);
        $name = get_class($obj);

        $obj = (is_null($obj)) ? $this : $obj;
        $arr = (is_object($obj)) ? get_object_vars($obj) : $obj;

        foreach( $arr as $key => $val)
        {
            if($val instanceof \DateTime)
            {
                $val->format('Y-m-d H:i:s');
                $arr[$key] =  $val->format('Y-m-d H:i:s');
            }
            else if(is_object($val))
            {
                if($level > $position){
                    $arr[$key] = $this->toArray($filterKeys, $val, $level, $position+1);
                }else{
                    $arr[$key] = $this->getId();
                }

            }

            if(count($filterKeys) > 0)
            {
                $arr = array_filter($arr, function($key) use ($filterKeys)
                {
                    return ( ! in_array($key, $filterKeys) );

                },ARRAY_FILTER_USE_KEY);
            }
        }
        return $arr;
    }

    public function extractObject($obj = null)
    {

        $obj = (is_null($obj)) ? $this : $obj;
        $arr = (is_object($obj)) ? $this->getHydrator()->extract($obj) : $obj;

        $res =[];
        foreach ($arr as $key => $val)
        {
            if(is_object($val))
            {
                if($val instanceof \DateTime)
                {
                    $val->format('Y-m-d H:i:s');
                    $res[$key] =  $val->format('Y-m-d H:i:s');
                }
                else if($val instanceof \Doctrine\ORM\PersistentCollection)
                {
                    foreach($val as $index => $value)
                    {
                        $res[$key][] = $value->extractObject($value);
                    }
                }
                else
                {
                    if($val instanceof \Ramsey\Uuid\Lazy\LazyUuidFromString){
                        $res[$key] = $val->__toString();
                    }else{
                        $res[$key] = self::extractObject($val);
                    }
                }
            }else{
                $res[$key] = $val;
            }
        }
        return $res;
    }

    public function getHydrator()
    {
        $config = new Configuration(get_class($this));
        $hydratorClass = $config->createFactory()->getHydratorClass();
        $hydrator = new $hydratorClass();
        return $hydrator;
    }
}