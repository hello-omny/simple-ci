<?php

namespace core\git;

use Exception;

/**
 * Class CloneAction
 * @package core\git
 */
class CloneAction extends AbstractAction
{
    private const CLONE_TEMPLATE = 'cd %s && %s clone --mirror %s %s 2>&1';

    /** @var array */
    public $repository;

    /**
     * @throws Exception
     */
    public function run()
    {
        $repositoryUrl = sprintf('%s:%s.git',
            self::REPOSITORY_URL_PREFIXES[$this->repository['type']],
            $this->repository['name']
        );

        $cmd = sprintf(self::CLONE_TEMPLATE,
            $this->config->repositoriesPath,
            $this->config->gitCommand,
            $repositoryUrl,
            $this->getRepositoryStorageName(
                $this->repository['type'],
                $this->repository['name']
            )
        );

        // system($cmd, $status);
        exec($cmd, $output, $status);
        $this->debug($output);

        if ($status !== 0) {
            $message = sprintf('Cannot clone repository %s. Errors: %s.',
                $repositoryUrl,
                print_r($output, true)
            );
            throw new Exception($message);
        }
    }
}
