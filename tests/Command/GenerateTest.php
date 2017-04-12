<?php
namespace Mill\Tests\Command;

use Mill\Command\Generate;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Command\Command
     */
    protected $command;

    /**
     * @var CommandTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected $config_file;

    public function setUp()
    {
        $application = new Application();
        $application->add(new Generate);

        $this->command = $application->find('generate');
        $this->tester = new CommandTester($this->command);

        $this->config_file = __DIR__ . '/../../resources/examples/mill.xml';
    }

    public function testGenerate()
    {
        $output_dir = tempnam(sys_get_temp_dir(), 'mill-generate-test-');
        if (file_exists($output_dir)) {
            unlink($output_dir);
        }

        mkdir($output_dir);

        $this->tester->execute([
            'command' => $this->command->getName(),
            '--config' => $this->config_file,
            'output' => $output_dir
        ]);

        $output = $this->tester->getDisplay();
        $this->assertNotContains('Running a dry run', $output);
        $this->assertContains('API version: 1.0', $output);
        $this->assertContains('API version: 1.1', $output);

        $this->assertSame([
            '.',
            '..',
            '1.0',
            '1.1',
            '1.1.1'
        ], scandir($output_dir));

        $blueprints_dir = __DIR__ . '/../../resources/examples/Showtimes/blueprints';
        $representations = [
            'Coded error',
            'Error',
            'Movie',
            'Person',
            'Theater'
        ];

        $resources = [
            'Movies',
            'Theaters'
        ];

        foreach (['1.0', '1.1', '1.1.1'] as $version) {
            foreach ($representations as $name) {
                $this->assertSame(
                    file_get_contents($blueprints_dir . '/' . $version . '/representations/' . $name . '.apib'),
                    file_get_contents($output_dir . '/' . $version . '/representations/' . $name . '.apib'),
                    'Generated representation `' . $name . '.apib` for version `' . $version . '`` does not match.'
                );
            }

            foreach ($resources as $name) {
                $this->assertSame(
                    file_get_contents($blueprints_dir . '/' . $version . '/resources/' . $name . '.apib'),
                    file_get_contents($output_dir . '/' . $version . '/resources/' . $name . '.apib'),
                    'Generated resource `' . $name . '.apib` for version `' . $version . '`` does not match.'
                );
            }
        }
    }

    public function testGenerateWithDryRun()
    {
        $this->tester->execute([
            'command' => $this->command->getName(),
            '--config' => $this->config_file,
            '--dry-run' => true,
            'output' => sys_get_temp_dir()
        ]);

        $output = $this->tester->getDisplay();
        $this->assertContains('Running a dry run', $output);
        $this->assertContains('API version: 1.0', $output);
        $this->assertContains('API version: 1.1', $output);
    }

    public function testGenerateWithDefaultVersion()
    {
        $this->tester->execute([
            'command' => $this->command->getName(),
            '--config' => $this->config_file,
            '--dry-run' => true,
            '--default' => true,
            'output' => sys_get_temp_dir()
        ]);

        $output = $this->tester->getDisplay();
        $this->assertContains('Running a dry run', $output);
        $this->assertNotContains('API version: 1.0', $output);
        $this->assertContains('API version: 1.1', $output);
    }

    public function testGenerateWithSpecificConstraint()
    {
        $this->tester->execute([
            'command' => $this->command->getName(),
            '--config' => $this->config_file,
            '--dry-run' => true,
            '--constraint' => '1.0',
            'output' => sys_get_temp_dir()
        ]);

        $output = $this->tester->getDisplay();
        $this->assertContains('Running a dry run', $output);
        $this->assertContains('API version: 1.0', $output);
        $this->assertNotContains('API version: 1.1', $output);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The supplied Mill configuration file does not exist.
     */
    public function testGenerateFailsOnInvalidConfigFile()
    {
        $this->tester->execute([
            'command' => $this->command->getName(),
            'output' => sys_get_temp_dir()
        ]);
    }

    public function testGenerateFailsOnInvalidVersionConstraint()
    {
        $this->tester->execute([
            'command' => $this->command->getName(),
            '--config' => $this->config_file,
            '--dry-run' => true,
            '--constraint' => '1.^',
            'output' => sys_get_temp_dir()
        ]);

        $output = $this->tester->getDisplay();
        $this->assertContains('1.^', $output);
        $this->assertContains('unrecognized schema', $output);
    }
}