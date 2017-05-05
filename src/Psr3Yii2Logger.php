<?php
namespace Sil\Psr3Adapters;

use Psr\Log\LogLevel as PsrLogLevel;
use Yii;

/**
 * A basic PSR-3 compliant logger that sends logs to Yii2's logging functions.
 * 
 * NOTE: Yii2 only provides error, warning, info, and trace levels, so the PSR-3
 *       log levels were mapped to those on a best-effort basis.
 */
class Psr3Yii2Logger extends LoggerBase
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
                Yii::error($messageWithContext);
                break;
            case PsrLogLevel::ALERT:
                Yii::error($messageWithContext);
                break;
            case PsrLogLevel::CRITICAL:
                Yii::error($messageWithContext);
                break;
            case PsrLogLevel::ERROR:
                Yii::error($messageWithContext);
                break;
            case PsrLogLevel::WARNING:
                Yii::warning($messageWithContext);
                break;
            case PsrLogLevel::NOTICE:
                Yii::info($messageWithContext);
                break;
            case PsrLogLevel::INFO:
                Yii::info($messageWithContext);
                break;
            case PsrLogLevel::DEBUG:
                Yii::trace($messageWithContext);
                break;
            default:
                throw new \Psr\Log\InvalidArgumentException(
                    'Unknown log level: ' . var_export($level, true),
                    1493998846
                );
        }
    }
}
