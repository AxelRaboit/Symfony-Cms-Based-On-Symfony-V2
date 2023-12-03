<?php

namespace App\Entity;

use App\Repository\BackendMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BackendMessageRepository::class)]
class BackendMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\ManyToOne(inversedBy: 'backendSentMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBackend $sender = null;

    #[ORM\ManyToOne(inversedBy: 'backendReceivedMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBackend $receiver = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getSender(): ?UserBackend
    {
        return $this->sender;
    }

    public function setSender(?UserBackend $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?UserBackend
    {
        return $this->receiver;
    }

    public function setReceiver(?UserBackend $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }
}
