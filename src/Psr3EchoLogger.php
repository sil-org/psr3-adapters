<?php
namespace Sil\Psr3Adapters;

/**
 * A basic PSR-3 compliant logger that merely echoes logs to the console
 * (primarily intended for use in tests).
 */
class Psr3EchoLogger extends LoggerBase
{
    /**
     * Log a message.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        echo sprintf(
            'LOG: [%s] %s',
            $level,
            $this->interpolate($message, $context)
        ) . PHP_EOL;
    }
}
