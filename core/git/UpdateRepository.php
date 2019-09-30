<?php

namespace core\git;

use core\base\AbstractComponent;
use core\Config;
use Exception;
use ReflectionException;

/**
 * Class UpdateRepository
 */
class UpdateRepository extends AbstractComponent
{
    /** @var Config */
    private $config;
    /** @var array */
    private $repository;

    /**
     * UpdateRepository constructor.
     * @param Config $config
     * @param array $repository
     * @throws ReflectionException
     */
    public function __construct(Config $config, array $repository)
    {
        $this->repository = $repository;
        $this->config = $config;

        parent::__construct([]);
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        $repositoryStorageDir = sprintf('%s/%s',
            $this->config->repositoriesPath,
            $this->getRepositoryStorageName(
                $this->repository['type'],
                $this->repository['name']
            )
        );

        $this->debug($repositoryStorageDir);

        if ($this->needClone($repositoryStorageDir)) {
            $handler = new CloneAction($this->config, [
                'repository' => $this->repository
            ]);
        } else {
            $handler = new FetchAction($this->config, [
                'repository' => $this->repository
            ]);
        }

        $handler->run();
    }

    /**
     * @param string $repositoryPath
     * @return bool
     */
    private function needClone(string $repositoryPath): bool
    {
        $headFile = sprintf('%s/HEAD', $repositoryPath);

        return !is_dir($repositoryPath) || !is_file($headFile);
    }
}
