# iotch/Log
Simple PSR-3 compliant logger with custom writers and formatters support

## Usage example
```php
<?php
use \iotch\Log;

// create logger
$logger = new Log\Manager('myLogger', new DateTimeZone('Europe/Minsk'));

// open stream to write logs to
$stream = fopen('php://memory', 'a');

// configure writer
$writer = new Log\Writer\StreamWriter($stream);
$writer
    ->setFormatter(new Log\Formatter\DefaultFormatter)
    ->catchAll();

// register writer
$logger->addWriter($writer);

// add to log
$logger->emergency('Emergency message');
$logger->log($logger::INFO, 'Info message');

print_r(stream_get_contents($stream, -1, 0));
```