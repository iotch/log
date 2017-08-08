<?php
namespace iotch\Log\Writer;

use iotch\Log\AbstractWriter;
use iotch\Log\Event;

class NullWriter extends AbstractWriter
{
    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
    }
}
