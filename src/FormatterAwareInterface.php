<?php
namespace iotch\Log;

interface FormatterAwareInterface
{
    /**
     * Sets the message formatter
     *
     * @param FormatterInterface $formatter
     * @return self
     */
    public function setFormatter(FormatterInterface $formatter);

    /**
     * Gets the message formatter
     *
     * @return FormatterInterface|null
     */
    public function getFormatter();
}
