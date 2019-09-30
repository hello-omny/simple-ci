<?php

namespace core\base;

use ReflectionException;

/**
 * Class AbstractComponent
 * @package core
 */
abstract class AbstractComponent extends Configurable implements ComponentInterface
{
    const REPOSITORY_STORAGE_REGEX_REPLACE = '/\//';

    /** @var bool */
    public $debug = false;

    /**
     * AbstractComponent constructor.
     * @param array $params
     * @throws ReflectionException
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * @param string $type
     * @param string $name
     * @return string
     */
    public function getRepositoryStorageName(string $type, string $name): string
    {
        return sprintf('%s-%s.git',
            $type,
            preg_replace(self::REPOSITORY_STORAGE_REGEX_REPLACE, '-', $name)
        );
    }

    /**
     * @param $output
     */
    public function debug($output): void
    {
        if ($this->debug) {
            var_dump($output);
        }
    }
}