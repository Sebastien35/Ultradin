<?php

namespace App\Entity;

use App\Repository\CountryIso3Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryIso3Repository::class)]
class CountryIso3
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_countryiso3 = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 3)]
    private ?string $iso3 = null;

    #[ORM\Column(length: 10)]
    private ?string $currency = null;

    public function getId(): ?int
    {
        return $this->id_countryiso3;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(string $iso3): static
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
}
