<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\Tests\App;

use OpenCodeCo\BusinessLayer\App\Builder\EventBuilder;
use OpenCodeCo\BusinessLayer\App\Builder\OrganizationBuilder;
use OpenCodeCo\BusinessLayer\App\Builder\ParticipantBuilder;
use OpenCodeCo\BusinessLayer\App\DTO\EventCreationDTO;
use OpenCodeCo\BusinessLayer\App\DTO\OrganizationCreationDTO;
use OpenCodeCo\BusinessLayer\App\DTO\ParticipantCreationDTO;
use OpenCodeCo\BusinessLayer\App\OrganizationService;
use OpenCodeCo\BusinessLayer\Domain\Event;
use OpenCodeCo\BusinessLayer\Domain\Exception\EventAlreadyExistsException;
use OpenCodeCo\BusinessLayer\Domain\Exception\EventFullException;
use OpenCodeCo\BusinessLayer\Domain\Exception\EventNotFoundException;
use OpenCodeCo\BusinessLayer\Domain\Exception\OrganizationAlreadyExistsException;
use OpenCodeCo\BusinessLayer\Domain\Organization;
use OpenCodeCo\BusinessLayer\Domain\OrganizationRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OrganizationServiceTest extends TestCase
{
    private OrganizationRepository|MockObject $repository;
    private OrganizationService $service;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->repository = $this->createMock(OrganizationRepository::class);

        $this->service = new OrganizationService(
            new OrganizationBuilder(),
            new EventBuilder(),
            new ParticipantBuilder(),
            $this->repository,
        );
    }

    public function testCreateOrganization(): void
    {
        $id = '8c91db3f-e2c4-4e45-8d46-98c1c861dca0';
        $name = 'PHPMG';
        $dto = new OrganizationCreationDTO($id, $name);

        $this->repository->expects(self::once())
            ->method('save')
            ->with(new Organization($id, $name, []));

        $this->service->create($dto);
    }

    public function testCreateOrganizationAlreadyExists(): void
    {
        $id = '8c91db3f-e2c4-4e45-8d46-98c1c861dca0';
        $name = 'PHPMG';
        $dto = new OrganizationCreationDTO($id, $name);

        $this->expectException(OrganizationAlreadyExistsException::class);

        $this->repository->expects(self::once())
            ->method('save')
            ->with(new Organization($id, $name, []))
            ->willThrowException(new OrganizationAlreadyExistsException());

        $this->service->create($dto);
    }

    public function testCreateEvent(): void
    {
        $organizationId = '8c91db3f-e2c4-4e45-8d46-98c1c861dca0';
        $orgName = 'PHPMG';
        $organization = new Organization($organizationId, $orgName);
        $slots = 50;
        $price = 0;

        $eventId = 'ffbc3ba8-5b46-4bbe-93b5-eb0060b58af7';
        $eventName = 'Workshop Hyperf';

        $event = new EventCreationDTO(
            $organizationId,
            $eventId,
            $eventName,
            $price,
            $slots
        );

        $this->repository->expects(self::once())
            ->method('getById')
            ->with($organizationId)
            ->willReturn($organization);

        $this->repository->expects(self::once())
            ->method('save');

        $this->service->createEvent($event);

        $this->assertEquals(json_encode($organization),
            sprintf(
                '{"id":"%s","name":"%s","events":{"%s":{"id":"%s","name":"%s","price_in_cents":%d,"slots":%s,"participants":{}}}}',
                $organizationId,
                $orgName,
                $eventId,
                $eventId,
                $eventName,
                $price,
                $slots
            )
        );
    }

    public function testCreateSameEventTwiceThrowsException(): void
    {
        $organizationId = '8c91db3f-e2c4-4e45-8d46-98c1c861dca0';
        $orgName = 'PHPMG';
        $organization = new Organization($organizationId, $orgName);
        $slots = 50;
        $price = 0;

        $eventId = 'ffbc3ba8-5b46-4bbe-93b5-eb0060b58af7';
        $eventName = 'Workshop Hyperf';

        $this->expectException(EventAlreadyExistsException::class);

        $event = new EventCreationDTO(
            $organizationId,
            $eventId,
            $eventName,
            $price,
            $slots
        );

        $this->repository->expects(self::exactly(2))
            ->method('getById')
            ->with($organizationId)
            ->willReturn($organization);

        $this->repository->expects(self::once())
            ->method('save');

        $this->service->createEvent($event);
        $this->service->createEvent($event);
    }

    public function testAddParticipants(): void
    {
        $organizationId = '8c91db3f-e2c4-4e45-8d46-98c1c861dca0';
        $orgName = 'PHPMG';
        $slots = 10;
        $price = 0;
        $eventId = 'ffbc3ba8-5b46-4bbe-93b5-eb0060b58af7';
        $eventName = 'Workshop Hyperf';

        $event = new Event($eventId, $eventName, $price, $slots);

        $organization = new Organization($organizationId, $orgName, [$event]);

        $participantId = 'af9ac56c-7ea2-409c-8ac0-a98e14d112b3';
        $participantName = 'Participant 1';
        $participantEmail = 'participant1@teste.com';

        $participantId2 = 'c3a6db5c-761e-480e-82c4-4398162e7eb6';
        $participantName2 = 'Participant 2';
        $participantEmail2 = 'participant2@teste.com';

        $participantCreation = new ParticipantCreationDTO(
            $organizationId,
            $eventId,
            $participantId,
            $participantName,
            $participantEmail
        );

        $participantCreation2 = new ParticipantCreationDTO(
            $organizationId,
            $eventId,
            $participantId2,
            $participantName2,
            $participantEmail2
        );

        $this->repository->expects(self::exactly(2))
            ->method('getById')
            ->with($organizationId)
            ->willReturn($organization);

        $this->repository->expects(self::exactly(2))
            ->method('save');

        $this->service->addParticipant($participantCreation);

        $this->assertEquals(json_encode($organization),
            sprintf(
                '{"id":"%s","name":"%s","events":{"%s":{"id":"%s","name":"%s","price_in_cents":%d,"slots":%s,"participants":{"%s":{"id":"%s","name":"%s","email":"%s"}}}}}',
                $organizationId,
                $orgName,
                $eventId,
                $eventId,
                $eventName,
                $price,
                $slots-1,
                $participantId,
                $participantId,
                $participantName,
                $participantEmail
            )
        );

        $this->service->addParticipant($participantCreation2);

        $this->assertEquals(json_encode($organization),
            sprintf(
                '{"id":"%s","name":"%s","events":{"%s":{"id":"%s","name":"%s","price_in_cents":%d,"slots":%s,"participants":{"%s":{"id":"%s","name":"%s","email":"%s"},"%s":{"id":"%s","name":"%s","email":"%s"}}}}}',
                $organizationId,
                $orgName,
                $eventId,
                $eventId,
                $eventName,
                $price,
                $slots-2,
                $participantId,
                $participantId,
                $participantName,
                $participantEmail,
                $participantId2,
                $participantId2,
                $participantName2,
                $participantEmail2,
            )
        );
    }


    public function testAddParticipantsToFullEventExceptionThrown(): void
    {
        $organizationId = '8c91db3f-e2c4-4e45-8d46-98c1c861dca0';
        $orgName = 'PHPMG';
        $slots = 5;
        $price = 0;
        $eventId = 'ffbc3ba8-5b46-4bbe-93b5-eb0060b58af7';
        $eventName = 'Workshop Hyperf';

        $event = new Event($eventId, $eventName, $price, $slots);

        $organization = new Organization($organizationId, $orgName, [$event]);

        $this->expectException(EventFullException::class);

        $this->repository->expects(self::exactly(6))
            ->method('getById')
            ->with($organizationId)
            ->willReturn($organization);

        $this->repository->expects(self::exactly(5))
            ->method('save');

        $participantsEntries = 6;
        while ($participantsEntries > 0) {
            $participantCreation = new ParticipantCreationDTO(
                $organizationId,
                $eventId,
                sprintf('af9ac56c-7ea2-409c-8ac0-a98e14d112b%d', $participantsEntries),
                sprintf('Participant %d', $participantsEntries),
                sprintf('participant%d@teste.com', $participantsEntries)
            );

            $this->service->addParticipant($participantCreation);

            $participantsEntries--;
        }
    }

    public function testAddParticipantsToEventNotFound(): void
    {
        $organizationId = '8c91db3f-e2c4-4e45-8d46-98c1c861dca0';
        $orgName = 'PHPMG';
        $eventId = 'ffbc3ba8-5b46-4bbe-93b5-eb0060b58af7';


        $organization = new Organization($organizationId, $orgName, []);

        $this->expectException(EventNotFoundException::class);

        $this->repository->expects(self::once())
            ->method('getById')
            ->with($organizationId)
            ->willReturn($organization);

        $this->repository->expects(self::never())
            ->method('save');

        $participantId = 'af9ac56c-7ea2-409c-8ac0-a98e14d112b3';
        $participantName = 'Participant 1';
        $participantEmail = 'participant1@teste.com';

        $participantCreation = new ParticipantCreationDTO(
            $organizationId,
            $eventId,
            $participantId,
            $participantName,
            $participantEmail
        );

        $this->service->addParticipant($participantCreation);
    }
}
