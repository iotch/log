<?php
namespace iotch\Log;

trait FormatterAwareTrait
{
    /**
     * registered formatter
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * {@inheritdoc}
     */
    final public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function getFormatter()
    {
        return $this->formatter;
    }
}
