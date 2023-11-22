<?php

namespace App\Entity;

use App\Repository\TimezoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimezoneRepository::class)]
class Timezone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    private ?string $countryCode = null;

    #[ORM\Column(length: 15)]
    private ?string $coordinates = null;

    #[ORM\Column(length: 32)]
    private ?string $timeZone = null;

    #[ORM\Column(length: 8)]
    private ?string $utcOffset = null;

    #[ORM\Column(length: 8)]
    private ?string $utcDstOffset = null;

    #[ORM\OneToMany(mappedBy: 'timezone', targetEntity: Country::class)]
    private Collection $countries;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(string $coordinates): static
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(string $timeZone): static
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getUtcOffset(): ?string
    {
        return $this->utcOffset;
    }

    public function setUtcOffset(string $utcOffset): static
    {
        $this->utcOffset = $utcOffset;

        return $this;
    }

    public function getUtcDstOffset(): ?string
    {
        return $this->utcDstOffset;
    }

    public function setUtcDstOffset(string $utcDstOffset): static
    {
        $this->utcDstOffset = $utcDstOffset;

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(Country $country): static
    {
        if (!$this->countries->contains($country)) {
            $this->countries->add($country);
            $country->setTimezone($this);
        }

        return $this;
    }

    public function removeCountry(Country $country): static
    {
        if ($this->countries->removeElement($country)) {
            // set the owning side to null (unless already changed)
            if ($country->getTimezone() === $this) {
                $country->setTimezone(null);
            }
        }

        return $this;
    }
}
