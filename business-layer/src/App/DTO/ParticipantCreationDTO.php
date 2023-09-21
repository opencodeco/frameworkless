<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\App\DTO;

class ParticipantCreationDTO
{
    public function __construct(
        public readonly string $orgId,
        public readonly string $eventId,
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
    ) {
    }
}
