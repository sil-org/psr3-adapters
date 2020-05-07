<?php
namespace Sil\Psr3Adapters\tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel as PsrLogLevel;
use Sil\Psr3Adapters\Psr3ConsoleLogger;
use Sil\Psr3Adapters\Psr3SamlLogger;
use Sil\Psr3Adapters\Psr3SyslogLogger;
use Sil\Psr3Adapters\Psr3Yii2Logger;

class Psr3EveryLoggerTest extends TestCase
{
    public function testLogKnownLogLevel()
    {
        // Arrange:
        putenv('SIMPLESAMLPHP_CONFIG_DIR=../vendor/simplesamlphp/simplesamlphp/config-templates/');
        $tests = [
            'console' => ['logger' => new Psr3ConsoleLogger()],
            'syslog' => ['logger' => new Psr3SyslogLogger('testName', null)],
            'saml' => ['logger' => new Psr3SamlLogger()],
            // Excluding, because it needs a rework, and this is just to get console working
            // 'yii2' => ['logger' => new Psr3Yii2Logger()],
        ];
        foreach ($tests as $testName => $testConfig) {
            $logger = $testConfig['logger'];
            $this->assertIsObject(
                $logger,
                sprintf(
                    'Expected an object for the logger for the %s test',
                    $testName
                )
            );
            $psrLevel = PsrLogLevel::CRITICAL; // Some known log level.

            // Act: if fails ungracefully, then test failed.
            $logger->log($psrLevel, 'Some message');
        }
    }

    // Console won't trigger exception on unknown level.

    public function testLogUnknownLogLevelSaml()
    {
        $logger = new Psr3SamlLogger();
        $this->expectException('\Psr\Log\InvalidArgumentException');
        $logger->log('unknown', 'A test message');
    }

    public function testLogUnknownLogLevelSyslog()
    {
        $logger = new Psr3SyslogLogger('testName', null);
        $this->expectException('\Psr\Log\InvalidArgumentException');
        $logger->log('unknown', 'A test message');
    }

    public function testLogUnknownLogLevelYii2()
    {
        $logger = new Psr3Yii2Logger();
        $this->expectException('\Psr\Log\InvalidArgumentException');
        $logger->log('unknown', 'A test message');
    }
}