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
        if (is_string($message)) {
            $message = $this->interpolate($message, $context);
        } elseif ( !empty ($context)) {
            throw new \Psr\Log\InvalidArgumentException(sprintf(
                'Cannot use context values (%s) for non-string message (%s).',
                var_export($context, true),
                var_export($message, true)
            ), 1494339014);
        }
        switch ($level) {
            case PsrLogLevel::EMERGENCY:
                Yii::error($message);
                break;
            case PsrLogLevel::ALERT:
                Yii::error($message);
                break;
            case PsrLogLevel::CRITICAL:
                Yii::error($message);
                break;
            case PsrLogLevel::ERROR:
                Yii::error($message);
                break;
            case PsrLogLevel::WARNING:
                Yii::warning($message);
                break;
            case PsrLogLevel::NOTICE:
                Yii::info($message);
                break;
            case PsrLogLevel::INFO:
                Yii::info($message);
                break;
            case PsrLogLevel::DEBUG:
                Yii::trace($message);
                break;
            default:
                throw new \Psr\Log\InvalidArgumentException(
                    'Unknown log level: ' . var_export($level, true),
                    1493998846
                );
        }
    }
}
