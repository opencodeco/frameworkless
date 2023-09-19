<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\App\DTO;

class EventCreationDTO
{
    public function __construct(
        public readonly string $organizationId,
        public readonly string $id,
        public readonly string $name,
        public readonly int $price,
        public readonly int $slots
    ) {
    }
}
