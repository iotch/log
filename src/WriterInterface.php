<?php
namespace iotch\Log;

interface WriterInterface extends FilterAwareInterface, FormatterAwareInterface
{
    /**
     * Handle all levels
     *
     * @return self
     */
    public function catchAll();

    /**
     * Sets levels to exclude from handling
     *
     * @param  int $levels
     * @return self
     */
    public function catchAllExcept(int ...$levels);

    /**
     * Sets explict levels to handle
     *
     * @param  int $levels
     * @return self
     */
    public function catchOnly(int ...$levels);

    /**
     * Sets the minimum handled level
     *
     * @param  int    $level
     * @return self
     */
    public function catchMinimum(int $level);

    /**
     * Sets the maximum handled level
     *
     * @param  int    $level
     * @return self
     */
    public function catchMaximum(int $level);

    /**
     * Sets event as terminal
     *
     * @return self
     */
    public function terminal();

    /**
     * Whether writer is terminal
     * and event will not be propagated further
     *
     * @return bool
     */
    public function isTerminal() : bool;

    /**
     * Checks if writer capable to handle event
     *
     * @param  int  $level
     * @return bool
     */
    public function isWriting(int $level) : bool;

    /**
     * Writes the event
     *
     * @param  Event $event
     * @return void
     */
    public function write(Event $event);
}
