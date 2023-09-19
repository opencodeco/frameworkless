<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\App\Builder;

use OpenCodeCo\BusinessLayer\App\DTO\ParticipantCreationDTO;
use OpenCodeCo\BusinessLayer\Domain\Participant;

class ParticipantBuilder
{
    public function buildFromCreationDTO(ParticipantCreationDTO $creationDTO): Participant
    {
        return new Participant($creationDTO->id, $creationDTO->name, $creationDTO->email);
    }
}
