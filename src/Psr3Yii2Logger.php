<?php
namespace Sil\Psr3Adapters;

use Psr\Log\InvalidArgumentException;
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
     * {@inheritdoc}
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $messageToLog = $this->interpolate($message, $context);

        switch ($level) {
            case PsrLogLevel::EMERGENCY:
            case PsrLogLevel::ALERT:
            case PsrLogLevel::CRITICAL:
            case PsrLogLevel::ERROR:
                Yii::error($messageToLog);
                break;
            case PsrLogLevel::WARNING:
                Yii::warning($messageToLog);
                break;
            case PsrLogLevel::NOTICE:
            case PsrLogLevel::INFO:
                Yii::info($messageToLog);
                break;
            case PsrLogLevel::DEBUG:
                Yii::debug($messageToLog);
                break;
            default:
                throw new InvalidArgumentException(
                    'Unknown log level: ' . var_export($level, true),
                    1493998846
                );
        }
    }
}
