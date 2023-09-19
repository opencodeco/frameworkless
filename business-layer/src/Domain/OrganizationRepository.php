<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\Domain;

use OpenCodeCo\BusinessLayer\Domain\Exception\OrganizationAlreadyExistsException;
use OpenCodeCo\BusinessLayer\Domain\Exception\OrganizationNotFoundException;

interface OrganizationRepository
{
    /**
     * @throws OrganizationNotFoundException
     */
    public function getById(string $id): Organization;

    /**
     * @throws OrganizationAlreadyExistsException
     */
    public function save(Organization $organization): void;
}
