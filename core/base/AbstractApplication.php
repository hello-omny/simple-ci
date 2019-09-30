<?php

namespace core\base;

use core\Config;
use core\git\ReleaseAction;
use core\Payload;
use core\git\UpdateRepository;
use core\composer\UpdateVendors;
use Exception;
use ReflectionException;

/**
 * Class AbstractApplication
 * @package core
 */
abstract class AbstractApplication extends Configurable
{
    /** @var bool */
    public $debug = false;

    /** @var array */
    protected $config;

    /** @var Payload */
    protected $payload;

    /**
     * Application constructor.
     * @param array $config
     * @param array $repositories
     * @throws Exception
     */
    public function __construct(array $config, array $repositories)
    {
        $this->config = new Config($config, $repositories);
        $this->payload = new Payload();

        parent::__construct($config);
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        $this->payload->run();
        $updatedRepository = $this->payload->getRepository();
        $updatedBranches = $this->payload->getUpdatedBranches();

        $repositoryConfig = $this->findRepositoryConfig($updatedRepository['name']);
        if ($repositoryConfig !== null) {
            $this->updateRepository($repositoryConfig);

            foreach ($updatedBranches as $branch) {
                $branchConfig = $this->findBranchConfig($repositoryConfig['branches'], $branch);
                if ($branchConfig !== null) {
                    $this->updateBranch($repositoryConfig, $branchConfig);
                    $this->updateVendors($branchConfig['deployPath']);
                }
            }
        }

        echo 'End';
    }

    /**
     * @param $repository
     * @throws ReflectionException
     * @throws Exception
     */
    public function updateRepository(array $repository)
    {
        $updater = new UpdateRepository($this->config, $repository);
        $updater->run();
    }

    /**
     * @param string $name
     * @return array|null
     */
    private function findRepositoryConfig(string $name): ?array
    {
        foreach ($this->config->repositories as $repository) {
            if ($repository['name'] === $name) {
                return $repository;
            }
        }
        return null;
    }

    /**
     * @param array $repositoryBranches
     * @param string $branchName
     * @return array|null
     */
    private function findBranchConfig(array $repositoryBranches, string $branchName): ?array
    {
        foreach ($repositoryBranches as $branch) {
            if ($branch['name'] === $branchName) {
                return $branch;
            }
        }

        return null;
    }

    /**
     * @param array $repository
     * @param array $branch
     * @throws ReflectionException
     * @throws Exception
     */
    private function updateBranch(array $repository, array $branch)
    {
        $handler = new ReleaseAction($this->config, [
            'repository' => $repository,
            'branch' => $branch
        ]);

        $handler->run();
    }

    /**
     * @param $dir
     * @throws ReflectionException
     */
    public function updateVendors($dir)
    {
        $updater = new UpdateVendors(
            [],
            $dir,
            $this->config->phpDir
        );

        $updater->run();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Deploy application";
    }
}
