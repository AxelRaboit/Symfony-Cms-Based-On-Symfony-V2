<?php

namespace App\Entity;

use App\Repository\DataEnumRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataEnumRepository::class)]
class DataEnum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\Column]
    private ?int $devKey = null;

    #[ORM\Column]
    private ?bool $isSystem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDevKey(): ?int
    {
        return $this->devKey;
    }

    public function setDevKey(int $devKey): static
    {
        $this->devKey = $devKey;

        return $this;
    }

    public function isIsSystem(): bool
    {
        return $this->isSystem;
    }

    public function setIsSystem(bool $isSystem): static
    {
        $this->isSystem = $isSystem;

        return $this;
    }
}
