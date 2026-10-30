<?php
namespace Sil\Psr3Adapters\tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel as PsrLogLevel;
use Sil\Psr3Adapters\Psr3EchoLogger;

class Psr3EchoLoggerTest extends TestCase
{
    public function testNoContext()
    {
        $this->expectOutputString('LOG: [info] A message' . PHP_EOL);
        (new Psr3EchoLogger())->log(PsrLogLevel::INFO, 'A message');
    }

    public function testContextIsLoggedAsKeyValuePairs()
    {
        $this->expectOutputString('LOG: [error] Login failed for bob user=bob attempts=3' . PHP_EOL);
        (new Psr3EchoLogger())->log(
            PsrLogLevel::ERROR,
            'Login failed for {user}',
            ['user' => 'bob', 'attempts' => 3]
        );
    }

    public function testContextValueFormatting()
    {
        $exception = new \RuntimeException('Boom');
        $expected = sprintf(
            'LOG: [error] Msg quoted="two words" empty="" yes=true no=false nothing=null float=1.5'
            . ' list=["a","b"] map={"k":"v"} stringable=abc obj=[stdClass] exception="RuntimeException: Boom at %s:%d"',
            $exception->getFile(),
            $exception->getLine()
        ) . PHP_EOL;

        $this->expectOutputString($expected);
        (new Psr3EchoLogger())->log(PsrLogLevel::ERROR, 'Msg', [
            'quoted' => 'two words',
            'empty' => '',
            'yes' => true,
            'no' => false,
            'nothing' => null,
            'float' => 1.5,
            'list' => ['a', 'b'],
            'map' => ['k' => 'v'],
            'stringable' => new StringableObject('a', 'bc'),
            'obj' => new \stdClass(),
            'exception' => $exception,
        ]);
    }
}
