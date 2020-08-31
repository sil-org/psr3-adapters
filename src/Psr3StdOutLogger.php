<?php
namespace Sil\Psr3Adapters;

/**
 * A basic PSR-3 compliant logger that writes logs to stdout. Note that this
 * will bypass any output buffering that PHP may be doing.
 */
class Psr3StdOutLogger extends LoggerBase
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
        $this->writeToStdOut(
            sprintf(
                'LOG: [%s] %s',
                $level,
                $this->interpolate($message, $context)
            ) . PHP_EOL
        );
    }

    private function writeToStdOut($message)
    {
        $fileHandle = fopen('php://stdout', 'w+');
        if ($fileHandle === false) {
            return;
        }
        fwrite($fileHandle, $message);
        fclose($fileHandle);
    }
}
