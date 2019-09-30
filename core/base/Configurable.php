<?php

namespace core\base;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class Configurable
 * @package core
 */
abstract class Configurable
{
    /**
     * Configurable constructor.
     * @param array $params
     * @throws ReflectionException
     */
    public function __construct(array $params = [])
    {
        if ($params !== []) {
            $this->load($params);
        }
    }

    /**
     * @param array $config
     * @throws ReflectionException
     */
    private function load(array $config): void
    {
        $ref = new ReflectionClass($this);
        $properties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (array_key_exists($propertyName, $config)) {
                $this->$propertyName = $config[$propertyName];
            }
        }
    }
}
