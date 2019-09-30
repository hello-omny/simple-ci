<?php

namespace core;

use core\base\AbstractComponent;
use core\git\AbstractAction;
use Exception;
use ReflectionException;

/**
 * Class Payload
 * @package core
 */
class Payload extends AbstractComponent
{
    /** @var string */
    private $event;
    /** @var string */
    private $hook;
    /** @var string */
    private $agent;
    /** @var string */
    private $addr;

    /** @var object */
    private $payload;
    /** @var array */
    private $repository;

    /**
     * Payload constructor.
     * @param array $params
     * @throws ReflectionException
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        $this->configureEvent();
        $this->configureHook();

        $this->agent = $_SERVER['HTTP_USER_AGENT'];
        $this->addr = $_SERVER['REMOTE_ADDR'];

        if (empty($event) && !empty($hook) && !empty($agent) && !empty($addr)) {
            throw new Exception('Cannot detect event, hook id, remote agent or address!');
        }

        parent::__construct($params);
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        if (array_key_exists('payload', $_POST)) {
            $this->payload = $_POST['payload'];
        } else { // new method
            $this->payload = json_decode(file_get_contents('php://input'));
        }

        if (empty($this->payload)) {
            throw new Exception('Empty payload.');
        }

        $this->setRepositoryName();
        $this->setRepositoryType();
    }

    /**
     * @return array
     */
    public function getUpdatedBranches(): array
    {
        $branches = [];
        switch ($this->repository['type']) {
            case AbstractAction::REPOSITORY_TYPE_BITBUCKET:
                $branches = $this->getBranchesForBitbucket();
                break;
            case AbstractAction::REPOSITORY_TYPE_GITHUB:
                $branches = $this->getBranchesForGithub();
                break;
        }
        return $branches;
    }

    /**
     * @return array
     */
    public function getRepository(): array
    {
        return $this->repository;
    }

    /**
     * @throws Exception
     */
    private function setRepositoryName()
    {
        if (!isset($this->payload->repository->full_name)) {
            throw new Exception('Repository name unknown.');
        }

        $this->repository['name'] = $this->payload->repository->full_name;
    }

    /**
     * @throws Exception
     */
    private function setRepositoryType()
    {
        // Bitbucket mode (changes list)...
        if (isset($this->payload->push->changes)) {
            $this->repository['type'] = AbstractAction::REPOSITORY_TYPE_BITBUCKET;
        }

        // Github mode (one branch)...
        if (isset($this->payload->ref)) {
            $this->repository['type'] = AbstractAction::REPOSITORY_TYPE_GITHUB;
        }

        if ($this->repository['type'] === null) {
            throw new Exception('Repository type unknown.');
        }
    }

    /**
     * @return array
     */
    private function getBranchesForBitbucket(): array
    {
        $result = [];
        foreach ($this->payload->push->changes as $change) {
            if (is_object($change->new) && $change->new->type == "branch") {
                $result[] = $change->new->name;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getBranchesForGithub(): array
    {
        $branchName = preg_replace('/refs\/heads\//', '', $this->payload->ref);
        return [$branchName];
    }

    private function configureEvent(): void
    {
        $this->event = null;

        if (array_key_exists('HTTP_X_EVENT_KEY', $_SERVER)) {
            $this->event = $_SERVER['HTTP_X_EVENT_KEY'];
        } else if (array_key_exists('HTTP_X_GITHUB_EVENT', $_SERVER)) {
            $this->event = $_SERVER['HTTP_X_GITHUB_EVENT'];
        }
    }

    private function configureHook(): void
    {
        $this->hook = null;

        if (array_key_exists('HTTP_X_HOOK_UUID', $_SERVER)) {
            $this->hook = $_SERVER['HTTP_X_HOOK_UUID'];
        } else if (array_key_exists('HTTP_X_GITHUB_DELIVERY', $_SERVER)) {
            $this->hook = $_SERVER['HTTP_X_GITHUB_DELIVERY'];
        }
    }
}
