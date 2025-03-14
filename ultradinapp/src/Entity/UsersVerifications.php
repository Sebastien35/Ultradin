<?php

namespace App\Entity;

use App\Repository\UsersVerificationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersVerificationsRepository::class)]
class UsersVerifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'usersVerifications')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id_user', nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $verified_at = null;

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\Column(length: 512)]
    private ?string $code_verification = null;

    #[ORM\Column(length: 255)]
    private ?string $type_verification = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?user
    {
        return $this->user_id;
    }

    public function setUserId(?user $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verified_at;
    }

    public function setVerifiedAt(\DateTimeImmutable $verified_at): static
    {
        $this->verified_at = $verified_at;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function getCodeVerification(): ?string
    {
        return $this->code_verification;
    }

    public function setCodeVerification(string $code_verification): static
    {
        $this->code_verification = $code_verification;

        return $this;
    }

    public function getTypeVerification(): ?string
    {
        return $this->type_verification;
    }

    public function setTypeVerification(string $type_verification): static
    {
        $this->type_verification = $type_verification;

        return $this;
    }
}
