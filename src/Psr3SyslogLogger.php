<?php
namespace Sil\Psr3Adapters;

use Monolog\Handler\SyslogHandler;
use Monolog\Level;
use Monolog\Logger;

/**
 * A basic PSR-3 compliant logger that sends logs to syslog.
 */
class Psr3SyslogLogger extends LoggerBase
{
    private $logger;
    
    public function __construct($name = 'name', $ident = 'ident')
    {
        $this->logger = new Logger($name);
        $this->logger->pushHandler(new SyslogHandler(
            $ident,
            LOG_USER,
            Level::Warning
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
