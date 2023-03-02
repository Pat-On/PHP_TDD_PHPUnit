<?php

declare(strict_types=1);

namespace App\Entity;

// we can achieve similar things using interface in that case
abstract class Entity
{
    abstract public function getId(): int;

    abstract public function toArray(): array;
}
