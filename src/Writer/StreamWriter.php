<?php
namespace iotch\Log\Writer;

use iotch\Log\AbstractWriter;
use iotch\Log\Event;

class StreamWriter extends AbstractWriter
{
    /**
     * resource
     * @var resource
     */
    protected $resource;

    /**
     * cought error message
     * @var string
     */
    protected $coughtError;

    /**
     * @param mixed $stream
     */
    public function __construct($stream)
    {
        // already created resource
        if (is_resource($stream)) {
            $this->resource = $stream;

        // stream string
        } elseif (is_string($stream)) {
            $this->resource = $this->createResource($stream);

        // unknown
        } else {
            throw new Exception(
                sprintf('Resource or string expected, %s given', $stream)
            );
        }
    }

    /**
     * Close resource
     */
    public function __destruct()
    {
        is_resource($this->resource) && fclose($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        if ($formatted = $event->getFormatted()) {
            flock($this->resource, LOCK_EX);
            fwrite($this->resource, $formatted . PHP_EOL);
            flock($this->resource, LOCK_UN);
        }
    }

    /**
     * Attemts to create a vaild resource from stream
     *
     * @return resource
     */
    protected function createResource(string $stream)
    {
        // if stream is a local file
        if ($filename = $this->getFilename($stream)) {
            $this->makeDir(dirname($filename));
        }

        // try to open the resource
        set_error_handler([$this, 'catchError']);
        $resource = @fopen($stream, 'a');
        restore_error_handler();

        if (! $resource) {
            throw new Exception(sprintf(
                'Unable to open "%s" (%s)',
                $stream,
                $this->coughtError
            ));
        }

        return $resource;
    }

    /**
     * Gets filename from stream
     *
     * @param  string $stream
     * @return string|false
     */
    private function getFilename(string $stream)
    {
        if (strpos($stream, '://') === false) {
            return $stream;
        } elseif ('file://' === substr($stream, 0, 7)) {
            return substr($stream, 7);
        }
        return false;
    }

    /**
     * Creates directory
     *
     * @param  string $path
     * @return bool
     */
    private function makeDir(string $path)
    {
        if (is_dir($path)) {
            return true;
        }

        set_error_handler([$this, 'catchError']);
        $created = @mkdir($path, 0775, true);
        restore_error_handler();

        if (! $created) {
            throw new Exception(sprintf(
                'Unable to create directory "%s" (%s)',
                $path,
                $this->coughtError
            ));
        }

        return true;
    }

    /**
     * Catches filesystem operations errors
     *
     * @param  int    $code
     * @param  string $message
     * @return void
     */
    private function catchError(int $code, string $message)
    {
        $this->coughtError = preg_replace(
            '~^(fopen|mkdir)\(.*?\):\s~',
            '',
            $message
        );
    }
}
