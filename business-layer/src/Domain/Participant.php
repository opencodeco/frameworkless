<?php

declare(strict_types=1);

namespace OpenCodeCo\BusinessLayer\Domain;

readonly class Participant implements \JsonSerializable
{
    public function __construct(
        public string $id,
        private string $name,
        private string $email
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
