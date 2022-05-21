<?php
/**
 * Created by PhpStorm.
 * User: ng
 * Date: 13/06/17
 * Time: 13:54
 */

namespace Siworks\Slim\Doctrine\Model;


Abstract Class AbstractModelRest extends AbstractModel implements IModel
{

    /**
     * @param Object $data
     *
     * return Object
     */
    public function create(array $data)
    {
        try
        {
            $entity = parent::create($data);
            $entityArr = $this->convertObjectToHateoas($entity);
        }
        catch (\Exception $e){
            throw new \Exception( "{$e->getMessage()} . (ABSMDRest-04013exc)", 4013);
        }
    }


    /**
     * @param  Array $data
     * @return Object | NULL
     * @throws \Exception
     */
    public function update(array $data)
    {
        try{
        }
        catch (PDOException $e){
            throw new PDOException( "{$e->getMessage()} . (ABSMDRest-04001exc)", 4001);
        }
    }

    /**
     * @param Integer $id
     *
     * @return Boolean
     */
    public function remove(array $data)
    {
        try
        {
        }
        catch (PDOException $e){
            throw new PDOException($e->getMessage() . " (ABSMDRest-04003exc)", 4003);
        }
    }

    public function findAll(array $data)
    {
        try
        {
            $arrObjs = $this->repository->getSimpleListBy($data['filters'], $data['order'], $data['limit'], $data['offset']);
            return $res;
        }
        catch(\PDOException $e){
            throw $e;
        }
    }

}