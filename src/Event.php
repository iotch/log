<?php
namespace iotch\Log;

use DateTime;

class Event
{
    /**
     * raw message
     * @var string
     */
    protected $message;

    /**
     * formatted message
     * @var string
     */
    protected $formatted;

    /**
     * level code
     * @var int
     */
    protected $level;

    /**
     * level name
     * @var string
     */
    protected $levelName;

    /**
     * time event occured on
     * @var DateTime
     */
    protected $time;

    /**
     * context
     * @var array
     */
    protected $context;

    /**
     * issuer name
     * @var string
     */
    protected $issuer;

    /**
     * extra data
     * @var array
     */
    protected $extras = [];

    /**
     * @param string      $message
     * @param int         $level
     * @param string      $levelName
     * @param DateTime    $time
     * @param array       $context
     * @param string|null $issuer
     */
    public function __construct(
        string $message,
        int $level,
        string $levelName,
        DateTime $time,
        array $context = [],
        string $issuer = null
    ) {
        $this->message   = $message;
        $this->level     = $level;
        $this->levelName = $levelName;
        $this->time      = $time;
        $this->context   = $context;
        $this->issuer    = $issuer;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFormatted();
    }

    /**
     * Sets the formatted message
     *
     * @param stringl $formatted
     * @return self
     */
    public function setFormatted(string $formatted = null)
    {
        $this->formatted = $formatted;
        return $this;
    }

    /**
     * Sets the extra
     *
     * @param  string $key
     * @param  mixed  $value
     * @return self
     */
    public function setExtra(string $key, $value)
    {
        if (isset($this->extras[$key])) {
            throw new Exception(sprintf('Extra key "%s" is already set', $key));
        }

        $this->extras[$key] = $value;
        return $this;
    }

    /**
     * Gets the message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Gets the formatted message
     *
     * @return string
     */
    public function getFormatted()
    {
        return $this->formatted ?? $this->message;
    }

    /**
     * Gets the level code
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Gets the level name
     *
     * @return string
     */
    public function getLevelName()
    {
        return $this->levelName;
    }

    /**
     * Gets the copy of time event occured on
     *
     * @return DateTime
     */
    public function getTime()
    {
        return clone $this->time;
    }

    /**
     * Gets the issuer name
     *
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * Gets the context
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Gets the extras
     *
     * @return array
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * Gets the extra by key
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getExtra(string $key, $default = null)
    {
        return $this->extras[$key] ?? $default;
    }
}
