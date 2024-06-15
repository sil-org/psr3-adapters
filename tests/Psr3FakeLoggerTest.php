<?php
namespace Sil\Psr3Adapters\tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel as PsrLogLevel;
use Sil\Psr3Adapters\Psr3FakeLogger;

class Psr3FakeLoggerTest extends TestCase
{
    public function testHasLogs()
    {
        $psr3FakeLogger = new Psr3FakeLogger();
        $psr3FakeLogger->log('warning', 'Some message');
        $hasLogs = $psr3FakeLogger->hasLogs();
        Assert::assertTrue($hasLogs, 'Missing expected log entry');
    }

    public function testHasSpecificLog()
    {
        $psr3FakeLogger = new Psr3FakeLogger();
        $psr3FakeLogger->log('warning', 'Specific message');

        $hasSpecificLog = $psr3FakeLogger->hasSpecificLog('Specific', false);
        Assert::assertTrue($hasSpecificLog, 'Missing expected log entry (loose)');

        $hasSpecificLog = $psr3FakeLogger->hasSpecificLog('Specific', true);
        Assert::assertFalse($hasSpecificLog, 'Unexpectedly found partial match (tight)');

        $hasSpecificLog = $psr3FakeLogger->hasSpecificLog('LOG: [warning] Specific message', true);
        Assert::assertTrue($hasSpecificLog, 'Missing expected specific log entry (tight)');
    }

    public function testObject()
    {
        $logger = new Psr3FakeLogger();
        $message = new StringableObject('{key}');
        $value = new StringableObject('needle');
        $logger->log(PsrLogLevel::ERROR, $message, ['key' => $value]);
        self::assertTrue($logger->hasSpecificLog('needle'));
    }
}
