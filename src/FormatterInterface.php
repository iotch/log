<?php
namespace iotch\Log;

interface FormatterInterface
{
    /**
     * Formats the event
     *
     * @param  Event $event
     * @return string
     */
    public function format(Event $event) : string;
}
