<?php
namespace iotch\Log;

trait FilterAwareTrait
{
    /**
     * registered filters
     * @var array
     */
    private $filters = [];

    /**
     * Sets the filter
     *
     * @param callable $filter
     * @return self
     */
    public function setFilter(callable $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Gets all filters
     *
     * @return array
     */
    public function getFilters() : array
    {
        return $this->filters;
    }

    /**
     * Applies filters to Event
     *
     * @param  Event  $event
     * @return Event
     */
    public function applyFilters(Event $event) : Event
    {
        foreach ($this->filters as $filter) {
            $event = $filter($event);
            if (! $event instanceof Event) {
                throw new Exception(sprintf(
                    'Event filter must return "%s" instance, %s returned',
                    Event::class,
                    gettype($event)
                ));
            }
        }

        return $event;
    }
}
