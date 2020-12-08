<?php


namespace App\Util;


use ReflectionClass;
use ReflectionException;

class UtilHelper
{
    public function getShortClassName($class): string
    {
        try {
            return (new ReflectionClass($class))->getShortName();
        } catch (ReflectionException $e) {
        }
    }

    /**
     * @param $dtoClass
     * @return string
     */
    public function getEntityClassFromDtoClass($dtoClass): string
    {
        return strtolower(substr($this->getShortClassName($dtoClass),0,-3));
    }
}