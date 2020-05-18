<?php
/**
 * @file docs/sphinx/Behat/DocExampleContext.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\ContextLib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Docs\Behat;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

use Korowai\Lib\Ldap\Behat\ExtLdapContext;

class DocExampleContext implements Context
{
    protected $stdout = null;
    protected $stderr = null;
    protected $status = null;

    protected function getExamplePath(string $file)
    {
        return realpath(__dir__.'/../examples/'.$file);
    }

    protected function getExampleFilePath(string $file)
    {
        $path = $this->getExamplePath($file);
        if ($path === false) {
            throw new \RuntimeException("example file does not exist: $file");
        }
        return $path;
    }

    protected function getTopPath(string $file)
    {
        return realpath(__dir__.'/../../../'.$file);
    }

    protected function getExampleFileContents(string $file, $default = '')
    {
        $path = $this->getExamplePath($file);
        if($path === false || !file_exists($path)) {
            return $default;
        }
        return file_get_contents($path);
    }

    protected function getExampleCmd(string $file)
    {
        $path = $this->getExampleFilePath($file);
        return implode(" ", [PHP_BINARY, "-d auto_prepend_file=vendor/autoload.php", $path]);
    }

    protected function getPhpUnitCmd(string $file)
    {
        $tst = $this->getExampleFilePath($file);
        $bin = $this->getTopPath('vendor/bin/phpunit');
        $xml = $this->getExamplePath('phpunit.xml');
        return implode(" ", [PHP_BINARY, $bin, '-c', $xml, '--colors=never', $tst]);
    }

    protected function runDocExample(string $file)
    {
        $cmd = $this->getExampleCmd($file);
        $this->runCommand($cmd);
    }

    protected function runPhpUnitExample(string $file)
    {
        $cmd = $this->getPhpUnitCmd($file);
        $this->runCommand($cmd);
    }

    protected function sanitizeOutput(string $string)
    {
        //$lines = explode(PHP_EOL, $string);
        static $patterns = [
            '/\r/',
        ];
        static $replaces = [
            '',
        ];
        return preg_replace($patterns, $replaces, $string);
    }

    protected function sanitizePhpUnitOutput(string $string)
    {
        //$lines = explode(PHP_EOL, $string);
        static $patterns = [
            '/\r/',
            '/^(PHPUnit )(?:\$version|\d+(?:\.\w+)*)( by Sebastian Bergmann and contributors\.)$/m',
            '/^(Time: )(?:(?:\d+)(?::\d+)?(?:\.\d+)? \w+)(, Memory: )(?:\d+(?:\.\d+)?(?: \d+)?( \w+)?)$/m',
            '/^(\/code\/packages\/testing\/Testing(?:\/\w+)+\.php):\d+$/m',
        ];
        static $replaces = [
            '',
            '\1$version\2',
            '\1$time\2$memory',
            '\1:##',
        ];
        return preg_replace($patterns, $replaces, $string);
    }

    protected function runCommand(string $cmd)
    {
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];
        $cwd = realpath(__dir__ . "/../../..");

        $proc = proc_open($cmd, $descriptorspec, $pipes, $cwd);

        if (!is_resource($proc)) {
            throw new \RuntimeException("could not execute example: $file");
        }

        fclose($pipes[0]);

        $this->stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $this->stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $this->status = proc_close($proc);
    }

    protected function resetExample()
    {
        $this->stdout = null;
        $this->stderr = null;
        $this->status = null;
    }

    /**
     * Clear and initialize data in the ldap database.
     *
     * @BeforeScenario @initLdapDbBeforeScenario
     * @BeforeSuite @initLdapDbBeforeSuite
     * @BeforeFeature @initLdapDbBeforeFeature
     * @AfterScenario @initLdapDbAfterScenario
     * @AfterSuite @initLdapDbAfterSuite
     * @AfterFeature @initLdapDbAfterFeature
     */
    public static function initLdapDb()
    {
        ExtLdapContext::initDb();
    }

    /**
     * @When I executed doc example :file
     */
    public function iExecutedDocExample(string $file)
    {
        $this->resetExample();
        $this->runDocExample($file);
    }

    /**
     * @When I tested :file with PHPUnit
     */
    public function iTestedWithPhpUnit(string $file)
    {
        $this->resetExample();
        $this->runPhpUnitExample($file);
    }

    /**
     * @Then I should see stdout from :file
     */
    public function iShouldSeeStdoutFrom(?string $file = null)
    {
        if (!is_null($file)) {
            Assert::assertSame(
                $this->getExampleFileContents($file),
                $this->sanitizeOutput($this->stdout)
            );
        }
    }

    /**
     * @Then I should see stderr from :file
     */
    public function iShouldSeeStderrFrom(?string $file = null)
    {
        if (!is_null($file)) {
            Assert::assertSame(
                $this->getExampleFileContents($file),
                $this->sanitizeOutput($this->stderr)
            );
        }
    }

    /**
     * @Then I should see exit code :code
     */
    public function iShouldSeeExitCode(int $code)
    {
        Assert::assertSame($code, $this->status);
    }

    /**
     * @Then I should see PHPunit stdout from :file
     */
    public function iShouldSeePhpUnitStdoutFrom(?string $file = null)
    {
        if (!is_null($file)) {
            $expect = $this->sanitizePhpUnitOutput($this->getExampleFileContents($file));
            $actual = $this->sanitizePhpUnitOutput($this->stdout);
            Assert::assertSame($expect, $actual);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
