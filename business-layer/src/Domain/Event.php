<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\Domain;

use OpenCodeCo\BusinessLayer\Domain\Exception\EventFullException;

class Event implements \JsonSerializable
{
    private \ArrayIterator $participants;

    public function __construct(
        public readonly string $id,
        private readonly string $name,
        private readonly int $price,
        private int $slots,
        array $participants = [],
    ) {
        $this->applyParticipants($participants);
    }

    private function applyParticipants(array $participants): void
    {
        $this->participants = new \ArrayIterator();

        array_map(fn (Participant $event) => $this->addParticipant($event), $participants);
    }

    public function addParticipant(Participant $participant): void
    {
        if (0 === $this->slots) {
            throw new EventFullException();
        }

        $this->participants->offsetSet($participant->id, $participant);
        $this->slots--;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price_in_cents' => $this->price,
            'slots' => $this->slots,
            'participants' => $this->participants,
        ];
    }
}
