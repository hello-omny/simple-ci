<?php

namespace core\git;

use core\base\AbstractComponent;
use core\Config;
use ReflectionException;

/**
 * Class AbstractAction
 * @package core\git
 */
class AbstractAction extends AbstractComponent
{
    public const REPOSITORY_TYPE_BITBUCKET = 'bitbucket';
    public const REPOSITORY_TYPE_GITHUB = 'github';

    public const REPOSITORY_URL_PREFIXES = array(
        self::REPOSITORY_TYPE_BITBUCKET => 'git@bitbucket.org',
        self::REPOSITORY_TYPE_GITHUB => 'git@github.com',
    );

    /** @var Config */
    protected $config;

    /**
     * AbstractAction constructor.
     * @param Config $config
     * @param array $params
     * @throws ReflectionException
     */
    public function __construct(Config $config, array $params = [])
    {
        $this->config = $config;

        parent::__construct($params);
    }

    public function run()
    {

    }

    /**
     * @param string $type
     * @param string $name
     * @return string
     */
    protected function getRepositoryStorageDir(string $type, string $name): string
    {
        $repositoryStorageDir = sprintf("%s/%s",
            $this->config->repositoriesPath,
            $this->getRepositoryStorageName($type, $name)
        );

        return $repositoryStorageDir;
    }
}