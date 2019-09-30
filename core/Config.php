<?php

namespace core;

use core\base\Configurable;
use ReflectionException;

/**
 * Class Config
 * @package core
 */
class Config extends Configurable
{
    /** @var bool */
    public $debug = true;

    /** @var string */
    public $gitCommand = 'git';
    /** @var string */
    public $phpDir = '/usr/bin/php';

    /** @var string */
    public $repositoriesPath;

    /** @var array */
    public $repositories;

    /**
     * Config constructor.
     * @param array $params
     * @param array $repositories
     * @throws ReflectionException
     */
    public function __construct(array $params = [], array $repositories = [])
    {
        $this->repositories = $repositories;

        parent::__construct($params);
    }
}
