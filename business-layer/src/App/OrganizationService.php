<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\App;

use OpenCodeCo\BusinessLayer\App\Builder\EventBuilder;
use OpenCodeCo\BusinessLayer\App\Builder\OrganizationBuilder;
use OpenCodeCo\BusinessLayer\App\Builder\ParticipantBuilder;
use OpenCodeCo\BusinessLayer\App\DTO\EventCreationDTO;
use OpenCodeCo\BusinessLayer\App\DTO\OrganizationCreationDTO;
use OpenCodeCo\BusinessLayer\App\DTO\ParticipantCreationDTO;
use OpenCodeCo\BusinessLayer\Domain\OrganizationRepository;

class OrganizationService
{
    public function __construct(
        private readonly OrganizationBuilder $organizationBuilder,
        private readonly EventBuilder $eventBuilder,
        private readonly ParticipantBuilder $participantBuilder,
        private readonly OrganizationRepository $repository,
    ) {
    }

    public function create(OrganizationCreationDTO $creationDTO): void
    {
        $org = $this->organizationBuilder->buildFromCreationDTO($creationDTO);

        $this->repository->save($org);
    }

    public function createEvent(EventCreationDTO $eventCreationDTO): void
    {
        $org = $this->repository->getById($eventCreationDTO->organizationId);

        $event = $this->eventBuilder->buildFromCreationDTO($eventCreationDTO);
        $org->addEvent($event);

        $this->repository->save($org);
    }

    public function addParticipant(ParticipantCreationDTO $participantCreationDTO): void
    {
        $org = $this->repository->getById($participantCreationDTO->orgId);
        $participant = $this->participantBuilder->buildFromCreationDTO($participantCreationDTO);

        $org->addParticipantToEvent($participantCreationDTO->eventId, $participant);

        $this->repository->save($org);
    }
}
