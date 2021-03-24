# PSR-3 Adapters
Various PSR3-compatible logging adapters.


## Adapter-specific notes

### Psr3ConsoleLogger
- Since this is ambiguous regarding whether logged messages are affected by
  PHP's output buffering, we recommend using either `Psr3EchoLogger` or
  `Psr3StdOutLogger` instead.

### Psr3EchoLogger
- This `echo`es out the log messages, allowing the output to be buffered so that
  it appears at the expected place within the rest of the output (such as in
  tests).

### Prs3StdOutLogger
- This writes to stdout, bypassing any output buffering that PHP might be doing.

### Psr3Yii2Logger
- Make sure your Yii config bootstraps the `log` component. In other words,
  include something like this in your Yii config:
  `'bootstrap' => ['log']`
