<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\App\Builder;

use OpenCodeCo\BusinessLayer\App\DTO\OrganizationCreationDTO;
use OpenCodeCo\BusinessLayer\Domain\Organization;

class OrganizationBuilder
{
    public function buildFromCreationDTO(OrganizationCreationDTO $creationDTO): Organization
    {
        return new Organization($creationDTO->id, $creationDTO->name);
    }
}
