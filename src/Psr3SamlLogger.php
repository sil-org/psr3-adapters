<?php
namespace Sil\Psr3Adapters;

use Psr\Log\LogLevel as PsrLogLevel;
use SimpleSAML\Logger;

/**
 * A minimalist wrapper library (for SimpleSAML\Logger) to make it PSR-3
 * compatible.
 */
class Psr3SamlLogger extends LoggerBase
{
    /**
     * Log a message.
     *
     * @param mixed $level One of the \Psr\Log\LogLevel::* constants.
     * @param string $message The message to log, possibly with {placeholder}s.
     * @param array $context An array of placeholder => value entries to insert
     *     into the message.
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $messageWithContext = $this->interpolate($message, $context);
        switch ($level) {
            case PsrLogLevel::EMERGENCY:
                Logger::emergency($messageWithContext);
                break;
            case PsrLogLevel::ALERT:
                Logger::alert($messageWithContext);
                break;
            case PsrLogLevel::CRITICAL:
                Logger::critical($messageWithContext);
                break;
            case PsrLogLevel::ERROR:
                Logger::error($messageWithContext);
                break;
            case PsrLogLevel::WARNING:
                Logger::warning($messageWithContext);
                break;
            case PsrLogLevel::NOTICE:
                Logger::notice($messageWithContext);
                break;
            case PsrLogLevel::INFO:
                Logger::info($messageWithContext);
                break;
            case PsrLogLevel::DEBUG:
                Logger::debug($messageWithContext);
                break;
            default:
                throw new \Psr\Log\InvalidArgumentException(
                    'Unknown log level: ' . var_export($level, true),
                    1485455196
                );
        }
    }
}
