<?php
namespace Sil\Psr3Adapters\tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel as PsrLogLevel;
use Sil\Psr3Adapters\Psr3EchoLogger;
use Sil\Psr3Adapters\Psr3FakeLogger;
use Sil\Psr3Adapters\Psr3SamlLogger;
use Sil\Psr3Adapters\Psr3StdOutLogger;
use Sil\Psr3Adapters\Psr3SyslogLogger;
use Sil\Psr3Adapters\Psr3Yii2Logger;
use yii\console\Application;

class Psr3EveryLoggerTest extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        require_once dirname(__DIR__) . "/vendor/yiisoft/yii2/Yii.php";
        new Application([
            'id' => 'Testing',
            'basePath' => dirname(__DIR__)
        ]);
        parent::__construct($name, $data, $dataName);
    }

    public function testLogKnownLogLevelYii2()
    {
        $this->checkSpecificLogger(new Psr3Yii2Logger());
    }

    public function testLogKnownLogLevelEcho()
    {
        $this->checkSpecificLogger(new Psr3EchoLogger());
    }

    public function testLogKnownLogLevelStdOut()
    {
        $this->checkSpecificLogger(new Psr3StdOutLogger());
    }

    public function testLogKnownLogLevelSyslog()
    {
        $this->checkSpecificLogger(new Psr3SyslogLogger());
    }

    public function testLogKnownLogLevelSaml()
    {
        putenv('SIMPLESAMLPHP_CONFIG_DIR=.');
        copy('../vendor/simplesamlphp/simplesamlphp/config/config.php.dist', './config.php');
        copy('../vendor/simplesamlphp/simplesamlphp/config/authsources.php.dist', './authsources.php');
        $this->checkSpecificLogger(new Psr3SamlLogger());
        unlink('./authsources.php');
        unlink('./config.php');
    }

    public function testLogKnownLogLevelFake()
    {
        $this->checkSpecificLogger(new Psr3FakeLogger());
    }

    /**
     * @param LoggerInterface $logger
     */
    private function checkSpecificLogger($logger)
    {
        self::assertIsObject(
            $logger,
            sprintf(
                'Expected an object for the logger for the %s test',
                get_class($logger)
            )
        );

        $psrLevels = [
            PsrLogLevel::EMERGENCY,
            PsrLogLevel::ALERT,
            PsrLogLevel::CRITICAL,
            PsrLogLevel::ERROR,
            PsrLogLevel::WARNING,
            PsrLogLevel::NOTICE,
            PsrLogLevel::INFO,
            PsrLogLevel::DEBUG,
        ];
        
        foreach ($psrLevels as $psrLevel) {
            // if fails ungracefully, then test failed.
            // Saml's DEBUG and INFO don't output by default.
            $logger->log($psrLevel, sprintf('Some %s message', $psrLevel));
        }
    }

    // Console won't trigger exception on unknown level.

    public function testLogUnknownLogLevelSaml()
    {
        $logger = new Psr3SamlLogger();
        $this->expectException('\Psr\Log\InvalidArgumentException');
        $logger->log('unknown', 'A test message');
    }

    // Syslog triggers exceptions in Monolog/Logger
    // for unknown levels, but not our code. No need to test. 

    public function testLogUnknownLogLevelYii2()
    {
        $logger = new Psr3Yii2Logger();
        $this->expectException('\Psr\Log\InvalidArgumentException');
        $logger->log('unknown', 'A test message');
    }

    public function testArrayYii2()
    {
        $logger = new Psr3Yii2Logger();
        $message = json_encode([
            'from' => ['log@example.com'],
            'to' => ['developer1@example.com', 'developer2@example.com'],
            'subject' => 'Log message',
            'formattedMessage' => ['Do not attempt to follow this example.'],
        ]);
        $logger->log(PsrLogLevel::ERROR, $message);
        self::assertTrue(true, 'No errors means it likely worked.');
    }
}
