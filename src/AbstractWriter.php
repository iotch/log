<?php
namespace iotch\Log;

abstract class AbstractWriter implements WriterInterface
{
    /**
     * interface methods
     */
    use FormatterAwareTrait;
    use FilterAwareTrait;

    /**
     * supported levels
     * @var array
     */
    private $levels = Manager::LEVELS;

    /**
     * levels to handle
     * @var array
     */
    private $handledLevels = [];

    /**
     * terminal flag
     * @var bool
     */
    private $isTerminal = false;

    /**
     * {@inheritdoc}
     */
    final public function catchAll()
    {
        $this->handledLevels = array_keys($this->levels);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function catchAllExcept(int ...$levels)
    {
        $this->handledLevels = array_diff(array_keys($this->levels), $levels);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function catchOnly(int ...$levels)
    {
        $this->handledLevels = array_intersect(array_keys($this->levels), $levels);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function catchMinimum(int $level)
    {
        $this->handledLevels = array_slice(array_keys($this->levels), -$level-1);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function catchMaximum(int $level)
    {
        $num = count(array_slice(array_keys($this->levels), $level));
        $this->handledLevels = array_slice(array_keys($this->levels), 0, $num);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function terminal()
    {
        $this->isTerminal = true;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function isTerminal() : bool
    {
        return $this->isTerminal;
    }

    /**
     * {@inheritdoc}
     */
    final public function isWriting(int $level) : bool
    {
        return in_array($level, $this->handledLevels);
    }

    /**
     * {@inheritdoc}
     */
    final public function write(Event $event)
    {
        // apply local filters
        $this->applyFilters($event);

        // apply local formatters
        if ($formatter = $this->getFormatter()) {
            $event->setFormatted($formatter->format(clone $event));
        }

        // process event
        $this->handle($event);
    }

    /**
     * Processes the event data
     *
     * @param  Event  $event
     * @return void
     */
    abstract public function handle(Event $event);
}
