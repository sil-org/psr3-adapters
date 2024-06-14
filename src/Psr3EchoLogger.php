<?php
namespace Sil\Psr3Adapters;

/**
 * A basic PSR-3 compliant logger that merely echoes logs to the console
 * (primarily intended for use in tests).
 */
class Psr3EchoLogger extends LoggerBase
{
    /**
     * {@inheritdoc}
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        echo sprintf(
            'LOG: [%s] %s',
            $level,
            $this->interpolate($message, $context)
        ) . PHP_EOL;
    }
}
