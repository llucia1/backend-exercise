<?php
declare(strict_types=1);

namespace Library\Books\Domain\Model;

use Library\Books\Domain\VO\PersonBirthYear;
use Library\Books\Domain\VO\PersonDeathYear;
use Library\Books\Domain\VO\PersonName;

final class PersonModel
{
    private PersonName $name;
    private ?PersonBirthYear $birthYear;
    private ?PersonDeathYear $deathYear;

    public function __construct(
        PersonName $name,
        ?PersonBirthYear $birthYear,
        ?PersonDeathYear $deathYear
    ) {
        $this->name = $name;
        $this->birthYear = $birthYear;
        $this->deathYear = $deathYear;
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function birthYear(): ?int
    {
        return $this->birthYear ? $this->birthYear->value() : null;
    }

    public function deathYear(): ?int
    {
        return $this->deathYear ? $this->deathYear->value() : null;
    }
}
