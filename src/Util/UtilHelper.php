<?php


namespace App\Util;


use ReflectionClass;

class UtilHelper
{
    /**
     * @param $class
     * @return string
     */
    public function getShortClassName($class): string
    {
        return (new ReflectionClass($class))->getShortName();
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