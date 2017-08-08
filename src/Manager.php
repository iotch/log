<?php
namespace iotch\Log;

use DateTime;
use DateTimeZone;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;

class Manager implements
    LoggerInterface,
    FilterAwareInterface,
    FormatterAwareInterface
{
    /**
     * Interface traits
     */
    use FilterAwareTrait;
    use FormatterAwareTrait;

    /**
     * Severity level codes
     * according to RFC 5424
     */
    const DEBUG     = 7;
    const INFO      = 6;
    const NOTICE    = 5;
    const WARNING   = 4;
    const ERROR     = 3;
    const CRITICAL  = 2;
    const ALERT     = 1;
    const EMERGENCY = 0;

    /**
     * Severity level names
     * @var array
     */
    const LEVELS = [
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];

    /**
     * instance name
     * @var string
     */
    protected $instanceName;

    /**
     * timeZone
     * @var DateTimeZone
     */
    protected $timeZone;

    /**
     * attached writers
     * @var array
     */
    protected $writers = [];


    public function __construct(string $name = null, DateTimeZone $timeZone = null)
    {
        $this->instanceName = $name;
        $this->timeZone     = $timeZone;
    }

    /**
     * Adds writer to the end of the queue
     *
     * @param WriterInterface $writer
     */
    public function addWriter(WriterInterface $writer)
    {
        $this->writers[] = $writer;
        return $this;
    }

    /**
     * Adds writers to the end of the queue
     *
     * @param array $writers
     */
    public function addWriters(array $writers)
    {
        foreach ($writers as $writer) {
            $this->addWriter($writer);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, array $context = [])
    {
        return $this->emit(self::EMERGENCY, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert($message, array $context = [])
    {
        return $this->emit(self::ALERT, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, array $context = [])
    {
        return $this->emit(self::CRITICAL, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = [])
    {
        return $this->emit(self::ERROR, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, array $context = [])
    {
        return $this->emit(self::WARNING, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, array $context = [])
    {
        return $this->emit(self::NOTICE, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = [])
    {
        return $this->emit(self::INFO, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, array $context = [])
    {
        return $this->emit(self::DEBUG, (string) $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
        if (! in_array($level, array_keys(self::LEVELS))) {
            throw new InvalidArgumentException('Unknown severity level');
        }

        return $this->emit($level, (string) $message, $context);
    }

    /**
     * Emits log event to the registered writers
     *
     * @param  int    $level
     * @param  string $message
     * @param  array  $context
     * @return bool
     */
    protected function emit(int $level, string $message, array $context = [])
    {
        if (empty($this->writers)) {
            return false;
        }

        // build time from DateTime without microseconds
        // due to performance reasons (x9 times slower)
        $time = new DateTime;
        if ($this->timeZone) {
            $time->setTimezone($this->timeZone);
        }

        // build event
        $event = new Event(
            $message,
            $level,
            self::LEVELS[$level],
            $time,
            $context,
            $this->instanceName
        );

        // apply global filters
        $this->applyFilters($event);

        // apply global formatter
        if ($formatter = $this->getFormatter()) {
            $event->setFormatted($formatter->format(clone $event));
        }

        // emit
        foreach ($this->writers as $k => $writer) {

            // skip if writer not capable
            if (false === $writer->isWriting($level)) {
                continue;
            }

            // write
            $writer->write(clone $event);

            // break if terminal writer
            if ($writer->isTerminal()) {
                break;
            }
        }

        return true;
    }
}
