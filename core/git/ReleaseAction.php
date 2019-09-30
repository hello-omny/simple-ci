<?php

namespace core\git;

use Exception;

/**
 * Class RealiseAction
 * @package core\git
 */
class ReleaseAction extends AbstractAction
{
    private const CMD_TEMPLATE = 'cd %s && GIT_WORK_TREE="%s" %s checkout -f %s';

    /** @var array */
    public $repository;
    /** @var array */
    public $branch;

    /**
     * @throws Exception
     */
    public function run()
    {
        $repositoryStorageDir = $this->getRepositoryStorageDir(
            $this->repository['type'],
            $this->repository['name']
        );
        $cmd = sprintf(
            self::CMD_TEMPLATE,
            $repositoryStorageDir,
            $this->branch['deployPath'],
            $this->config->gitCommand,
            $this->branch['name'],
        );
        $this->debug($cmd);

        exec($cmd, $output, $status);
        $this->debug($output);

        if ($status !== 0) {
            throw new Exception('Release failed. See log.');
        }
    }
}
