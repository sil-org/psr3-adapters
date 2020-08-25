<?php
namespace Sil\Psr3Adapters;

/**
 * A basic PSR-3 compliant logger that merely echoes logs to the console
 * (primarily intended for use in tests).
 */
class Psr3FakeLogger extends LoggerBase
{
    /** @var string[] $log */
    private $log = [];

    /**
     * Log a message.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $this->log[] = 
            sprintf(
                'LOG: [%s] %s',
                $level,
                $this->interpolate($message, $context)
            );
    }

    /**
     * @return bool
     */
    public function hasLogs()
    {
        return !empty($this->log);
    }

    /**
     * @param string $needle
     * @param bool $strict
     * @return bool
     */
    public function hasSpecificLog($needle, $strict = false)
    {
        
        $strictMatch = false;
        $looseMatch = false;
        foreach ($this->log as $entry) {
            $strictMatch = ($entry === $needle) || $strictMatch;
            $position = strpos($entry, $needle);
            $looseMatch = ($position !== false) || $looseMatch;
        }
        $returnValue = $strict ? $strictMatch : $looseMatch;
        return $returnValue;
    }
}
