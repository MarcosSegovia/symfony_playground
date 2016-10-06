<?php

namespace User\Domain;

use Ramsey\Uuid\Uuid;

final class SkillId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
    }

    public static function fromString(string $id) : self
    {
        return new static($id);
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
