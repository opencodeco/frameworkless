<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\App\DTO;

class OrganizationCreationDTO
{
    public function __construct(public readonly string $id, public readonly string $name)
    {
    }
}
