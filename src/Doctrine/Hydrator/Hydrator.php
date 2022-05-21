<?php
    namespace Siworks\Slim\Doctrine\Hydrator;
    use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
    use DateTime;
    class Hydrator extends DoctrineObject {
        protected function handleTypeConversions($value, $typeOfField) {
            
            if ( $typeOfField == 'datetime' || $typeOfField == 'date' ) {
                if(is_string($value)){
                    return new DateTime($value);
                }
                
            }
            return parent::handleTypeConversions($value, $typeOfField);
        }
    }