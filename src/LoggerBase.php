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

    /**
     * Interpolate context values into the given string, then append every
     * context entry as key=value pairs (if there are any).
     *
     * @param string|\Stringable $message The message (potentially with placeholders).
     * @param array $context (Optional:) The array of values to insert into the
     *     corresponding placeholders and to append to the message.
     * @return string The resulting string.
     */
    protected function interpolateWithContext(string|\Stringable $message, array $context = []): string
    {
        $result = $this->interpolate($message, $context);
        if (!empty($context)) {
            $result .= ' ' . $this->formatContext($context);
        }
        return $result;
    }

    /**
     * Format the given context as space-separated key=value pairs.
     *
     * @param array $context The context array.
     * @return string The resulting string, such as `user=bob attempts=3`.
     */
    protected function formatContext(array $context): string
    {
        $pairs = [];
        foreach ($context as $key => $value) {
            $pairs[] = $key . '=' . $this->formatContextValue($value);
        }
        return implode(' ', $pairs);
    }

    /**
     * Convert a single context value to a string suitable for a key=value pair.
     * Strings containing whitespace, quotes, or equals signs (or empty strings)
     * are quoted so that the pairs remain unambiguous.
     *
     * @param mixed $value The context value.
     * @return string The formatted value.
     */
    protected function formatContextValue(mixed $value): string
    {
        if ($value instanceof \Throwable) {
            $value = sprintf(
                '%s: %s at %s:%d',
                get_class($value),
                $value->getMessage(),
                $value->getFile(),
                $value->getLine()
            );
        } elseif ($value instanceof \Stringable) {
            $value = (string)$value;
        }

        if (is_string($value)) {
            if ($value !== '' && preg_match('/[\s"=]/', $value) !== 1) {
                return $value;
            }
            return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return 'null';
        }

        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }

        if (is_array($value) || $value instanceof \JsonSerializable) {
            $json = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
            if ($json !== false) {
                return $json;
            }
        }

        return '[' . get_debug_type($value) . ']';
    }
}
