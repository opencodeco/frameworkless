<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\Domain;

use ArrayIterator;
use OpenCodeCo\BusinessLayer\Domain\Exception\EventAlreadyExistsException;
use OpenCodeCo\BusinessLayer\Domain\Exception\EventNotFoundException;

class Organization implements \JsonSerializable
{
    private ArrayIterator $events;

    public function __construct(
        private readonly string $id,
        private readonly string $name,
        array $events = []
    ) {
        $this->applyEvents($events);
    }

    private function applyEvents(array $events): void
    {
        $this->events = new ArrayIterator();

        array_map(fn (Event $event) => $this->addEvent($event), $events);
    }

    public function addEvent(Event $event): void
    {
        if ($this->events->offsetExists($event->getId())) {
            throw new EventAlreadyExistsException();
        }

        $this->events->offsetSet($event->getId(), $event);
    }

    public function addParticipantToEvent(string $eventId, Participant $participant): void
    {
        if (!$this->events->offsetExists($eventId)) {
            throw new EventNotFoundException();
        }

        /** @var Event $event */
        $event = $this->events->offsetGet($eventId);
        $event->addParticipant($participant);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'events' => $this->events,
        ];
    }
}
