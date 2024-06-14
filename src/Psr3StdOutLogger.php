<?php
namespace Sil\Psr3Adapters;

/**
 * A basic PSR-3 compliant logger that writes logs to stdout.
 *
 * NOTE: This bypasses any output buffering that PHP may be doing.
 */
class Psr3StdOutLogger extends LoggerBase
{
    /**
     * {@inheritdoc}
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $this->writeToStdOut(
            sprintf(
                'LOG: [%s] %s',
                $level,
                $this->interpolate($message, $context)
            ) . PHP_EOL
        );
    }

    private function writeToStdOut(string $message): void
    {
        $fileHandle = fopen('php://stdout', 'w+');
        if ($fileHandle === false) {
            return;
        }
        fwrite($fileHandle, $message);
        fclose($fileHandle);
    }
}
