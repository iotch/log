<?php
namespace iotch\Log\Formatter;

use iotch\Log\Event;
use iotch\Log\FormatterInterface;

class DefaultFormatter implements FormatterInterface
{
    /**
     * date format
     * @var string
     */
    protected $dateFormat;

    /**
     * @param string|null $dateFormat
     */
    public function __construct(string $dateFormat = 'r')
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function format(Event $event) : string
    {
        $message = $event->getMessage();
        $time    = $event->getTime()->format($this->dateFormat);
        $issuer  = $event->getIssuer();
        $level   = $event->getLevelName();
        $context = $event->getContext();
        $extras  = $event->getExtras();

        $jsonOptions = JSON_UNESCAPED_UNICODE;
        $context = $context ? json_encode($context, $jsonOptions) : null;
        $extras  = $extras ? json_encode($extras, $jsonOptions) : null;

        $formatted = "[$time][";

        if ($issuer) {
            $formatted .= "$issuer.";
        }

        $formatted .= "$level] $message";

        if ($context) {
            $formatted .= ", context: $context";
        }

        if ($extras) {
            $formatted .= ", extras: $extras";
        }

        return $formatted;
    }
}
