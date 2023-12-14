<?php

namespace App\Entity;

use App\Repository\UserBackendRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserBackendRepository::class)]
class UserBackend implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var string[] An array of roles
     *
     * @ORM\Column(type="json")
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastLoginAt = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserBackendInformation $information = null;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: BackendMessage::class)]
    private Collection $backendSentMessages;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: BackendMessage::class)]
    private Collection $backendReceivedMessages;

    public function __construct()
    {
        $this->backendSentMessages = new ArrayCollection();
        $this->backendReceivedMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getLastLoginAt(): ?DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?DateTimeImmutable $lastLoginAt): static
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getInformation(): ?UserBackendInformation
    {
        return $this->information;
    }

    public function setInformation(UserBackendInformation $information): static
    {
        // set the owning side of the relation if necessary
        if ($information->getUser() !== $this) {
            $information->setUser($this);
        }

        $this->information = $information;

        return $this;
    }

    /**
     * @return Collection<int, BackendMessage>
     */
    public function getBackendSentMessages(): Collection
    {
        return $this->backendSentMessages;
    }

    public function addBackendSentMessage(BackendMessage $backendSentMessage): static
    {
        if (!$this->backendSentMessages->contains($backendSentMessage)) {
            $this->backendSentMessages->add($backendSentMessage);
            $backendSentMessage->setSender($this);
        }

        return $this;
    }

    public function removeBackendSentMessage(BackendMessage $backendSentMessage): static
    {
        if ($this->backendSentMessages->removeElement($backendSentMessage)) {
            // set the owning side to null (unless already changed)
            if ($backendSentMessage->getSender() === $this) {
                $backendSentMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BackendMessage>
     */
    public function getBackendReceivedMessages(): Collection
    {
        return $this->backendReceivedMessages;
    }

    public function addBackendReceivedMessage(BackendMessage $backendReceivedMessage): static
    {
        if (!$this->backendReceivedMessages->contains($backendReceivedMessage)) {
            $this->backendReceivedMessages->add($backendReceivedMessage);
            $backendReceivedMessage->setReceiver($this);
        }

        return $this;
    }

    public function removeBackendReceivedMessage(BackendMessage $backendReceivedMessage): static
    {
        if ($this->backendReceivedMessages->removeElement($backendReceivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($backendReceivedMessage->getReceiver() === $this) {
                $backendReceivedMessage->setReceiver(null);
            }
        }

        return $this;
    }
}
