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
     * Interpolate context values into the given string.
     *
     * This is based heavily on the example implementation here:
     * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
     *
     * @param string|\Stringable $message The message (potentially with placeholders).
     * @param array $context (Optional:) The array of values to insert into the
     *     corresponding placeholders.
     * @return string The resulting string.
     */
    protected function interpolate(string|\Stringable $message, array $context = []): string
    {
        // Build a replacement array with braces around the context keys.
        $replace = [];
        foreach ($context as $key => $value) {
            // Check that the value can be cast to string.
            if (!is_array($value) && (!is_object($value) || $value instanceof \Stringable)) {
                $replace['{' . $key . '}'] = $value;
            }
        }

        // Interpolate replacement values into the message.
        return strtr($message, $replace);
    }
}
