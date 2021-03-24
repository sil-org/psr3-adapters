<?php
namespace Sil\Psr3Adapters;

use Psr\Log\AbstractLogger;

/**
 * A base class that implements the interpolate function, to reduce duplication
 * in logger classes provided by this project.
 */
abstract class LoggerBase extends AbstractLogger
{
    /**
     * @var bool Whether the system this adapter connects to can support an
     *     array as the log message (for more structured logs).
     */
    protected $isArraySupported = false;
    
    /**
     * Interpolate context values into the message placeholders.
     * 
     * @param string $message The data to be logged.
     * @param array $context (Optional:) The array of values to insert into the
     *     corresponding placeholders.
     * @return string The resulting log message.
     */
    protected function interpolate($message, array $context = []): string
    {
        if (is_string($message)) {
            return $this->interpolateString($message, $context);
        }
        
        return $this->interpolateString(
            var_export($message, true),
            $context
        );
    }

    /**
     * Interpolate context values into the message placeholders.
     *
     * @param array $message The data to be logged.
     * @param array $context (Optional:) The array of values to insert into the
     *     corresponding placeholders.
     * @return array The resulting log message.
     */
    protected function interpolateArray($message, array $context = []): array
    {
        return \array_merge($message, $context);
    }

    /**
     * Interpolate context values into the given string.
     * 
     * This is based heavily on the example implementation here:
     * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
     * 
     * @param string $message The message (potentially with placeholders).
     * @param array $context (Optional:) The array of values to insert into the
     *     corresponding placeholders.
     * @return string The resulting string.
     */
    private function interpolateString($message, array $context = [])
    {
        // Build a replacement array with braces around the context keys.
        $replace = [];
        foreach ($context as $key => $value) {
            // Check that the value can be cast to string.
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = $value;
            }
        }

        // Interpolate replacement values into the message.
        $result = strtr($message, $replace);
        
        if (is_string($result)) {
            return $result;
        }
        
        /* If something went wrong, return the original message (with a
         * warning).  */
        return sprintf(
            '%s (WARNING: Unable to interpolate the context values into the message. %s).',
            $message,
            var_export($replace, true)
        );
    }
}
