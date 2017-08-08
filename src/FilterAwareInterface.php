<?php
namespace iotch\Log;

interface FilterAwareInterface
{
    /**
     * Sets the filter
     *
     * @param callable $filter
     * @return self
     */
    public function setFilter(callable $filter);

    /**
     * Gets all filters
     *
     * @return array
     */
    public function getFilters() : array;

    /**
     * Applies registred filters
     *
     * @param  Event  $event
     * @return Event
     */
    public function applyFilters(Event $event) : Event;
}
