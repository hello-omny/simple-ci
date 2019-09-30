<?php

namespace core\git;

use Exception;

/**
 * Class FetchAction
 * @package core\git
 */
class FetchAction extends AbstractAction
{
    private const FETCH_TEMPLATE = 'cd %s && %s fetch 2>&1';

    /** @var array */
    public $repository;

    /**
     * @throws Exception
     */
    public function run()
    {
        $repositoryStorageDir = $this->getRepositoryStorageDir(
            $this->repository['type'],
            $this->repository['name']
        );

        $cmd = sprintf(self::FETCH_TEMPLATE,
            $repositoryStorageDir,
            $this->config->gitCommand
        );

        // system($cmd, $status);
        exec($cmd, $output, $status);
        $this->debug($output);

        if ($status !== 0) {
            $message = sprintf("Cannot fetch repository '%s' in '%s': %s",
                $this->repository['name'],
                $repositoryStorageDir,
                print_r($output, true)
            );
            throw new Exception($message);
        }
    }
}
