<?php

namespace core\composer;

use core\base\AbstractComponent;
use core\base\ComponentInterface;
use ReflectionException;

/**
 * Class UpdateVendors
 */
class UpdateVendors extends AbstractComponent implements ComponentInterface
{
    private const COMPOSER_EXEC_NAME = 'composer.phar';

    /** @var bool */
    public $debug = false;
    /** @var bool */
    public $removeComposer = true;

    /** @var string */
    private $codeDir;
    /** @var string */
    private $phpDir;

    /**
     * UpdateVendors constructor.
     * @param array $params
     * @param string $codeDir
     * @param string $phpDir
     * @throws ReflectionException
     */
    public function __construct(
        array $params,
        string $codeDir,
        string $phpDir
    )
    {
        $this->codeDir = $codeDir;
        $this->phpDir = $phpDir;

        parent::__construct($params);
    }

    public function run()
    {
        $this->debug('Run vendor update.');
        $this->downloadComposer();
        $this->update();

        if ($this->removeComposer) {
            $this->deleteComposerPhar();
        }
    }

    private function update(): void
    {
        $cmd = sprintf('cd %s && %s %s i --no-dev --optimize-autoloader -v',
            $this->codeDir,
            $this->phpDir,
            self::COMPOSER_EXEC_NAME
        );

        exec($cmd, $output, $status);
        $this->debug($output);
    }

    private function downloadComposer(): void
    {
        copy(
            'https://getcomposer.org/installer',
            sprintf('%s/composer-setup.php', $this->codeDir)
        );
        $cmd = sprintf(
            'cd %s && %s composer-setup.php --filename=%s',
            $this->codeDir,
            $this->phpDir,
            self::COMPOSER_EXEC_NAME
        );
        exec($cmd, $output, $status);
        $this->debug($output);

        $cmd = sprintf('cd %s && rm -rf composer-setup.php',
            $this->codeDir
        );
        exec($cmd, $output, $status);
        $this->debug($output);
    }

    private function deleteComposerPhar(): void
    {
        $cmd = sprintf(
            'cd %s && rm -rf %s',
            $this->codeDir,
            self::COMPOSER_EXEC_NAME
        );
        exec($cmd, $output, $status);
        $this->debug($output);
    }
}
