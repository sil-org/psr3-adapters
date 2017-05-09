# PSR-3 Adapters
Various PSR3-compatible logging adapters.


## Adapter-specific notes

### Psr3Yii2Logger
- Make sure your Yii config bootstraps the `log` componenet. In other words,
  include something like this in your Yii config:
  `'bootstrap' => ['log']`
