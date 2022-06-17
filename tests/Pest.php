<?php

use IBroStudio\FileDataManager\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function getPrivateProperty($className, $propertyName)
{
    $reflector = new ReflectionClass($className);
    $property = $reflector->getProperty($propertyName);
    $property->setAccessible(true);

    return $property;
}
