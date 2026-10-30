# PSR-3 Adapters
Various PSR3-compatible logging adapters.


## Context

Context values are interpolated into `{placeholder}`s in the message. In addition, `Psr3EchoLogger`,
`Psr3StdOutLogger`, `Psr3SamlLogger`, and `Psr3Yii2Logger` append every context entry to the logged message as
`key=value` pairs, for example:

```
LOG: [error] Login failed for bob user=bob attempts=3
```

Strings containing whitespace, quotes, or `=` are quoted; arrays are JSON-encoded; exceptions are logged as
`Class: message at file:line`.

## Adapter-specific notes

### Psr3EchoLogger

A basic PSR-3 compliant logger that merely echoes logs to the console (primarily intended for use in tests).

This will `echo` out the log messages, allowing the output to be buffered so that it appears at the expected place within the rest of the output (such as in tests).

### Psr3FakeLogger

A basic PSR-3 compliant logger that stores log entries in an array. This allows confirmation that logging has occurred using hasLogs and hasSpecificLog methods.
 
### Psr3SamlLogger

A minimalist wrapper library (for SimpleSAML\Logger) to make it PSR-3 compatible.

### Psr3StdOutLogger

A basic PSR-3 compliant logger that writes logs to stdout. This bypasses any output buffering that PHP may be doing.

### Psr3SyslogLogger

A basic PSR-3 compliant logger that sends logs to syslog.

### Psr3Yii2Logger

A basic PSR-3 compliant logger that sends logs to Yii2's logging functions. 

NOTE: Yii2 only provides error, warning, info, and trace levels, so the PSR-3 log levels were mapped to those on a best-effort basis.

Make sure your Yii config bootstraps the `log` component. In other words, include something like this in your Yii config: `'bootstrap' => ['log']`
