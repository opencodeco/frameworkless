<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\App\Builder;

use OpenCodeCo\BusinessLayer\App\DTO\EventCreationDTO;
use OpenCodeCo\BusinessLayer\Domain\Event;

class EventBuilder
{
    public function buildFromCreationDTO(EventCreationDTO $creationDTO): Event
    {
        return new Event($creationDTO->id, $creationDTO->name, $creationDTO->price, $creationDTO->slots, []);
    }
}
