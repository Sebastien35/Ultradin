<?php

namespace App\Entity;

use App\Repository\CurrencyExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyExchangeRateRepository::class)]
class CurrencyExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $currency_code = null;

    #[ORM\Column]
    private ?float $rate_to_eur = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $iat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRateToEur(): ?float
    {
        return $this->rate_to_eur;
    }

    public function setRateToEur(float $rate_to_eur): static
    {
        $this->rate_to_eur = $rate_to_eur;

        return $this;
    }

    
    public function getCurrencyCode(): ?string
    {
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currency_code): static
    {
        $this->currency_code = $currency_code;

        return $this;
    }

    public function getIat(): ?\DateTimeInterface
    {
        return $this->iat;
    }

    public function setIat(\DateTimeInterface $iat): static
    {
        $this->iat = $iat;

        return $this;
    }
}
