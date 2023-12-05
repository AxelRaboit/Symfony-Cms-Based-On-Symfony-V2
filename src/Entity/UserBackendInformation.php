<?php

namespace App\Entity;

use App\Repository\UserBackendInformationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: UserBackendInformationRepository::class)]
#[Vich\Uploadable]
class UserBackendInformation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'information', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserBackend $user = null;

    #[Vich\UploadableField(mapping: 'picturesProfileImages', fileNameProperty: 'pictureProfileName', size: 'pictureProfileSize')]
    private ?File $pictureProfileFile = null;

    #[ORM\Column(nullable: true)]
    private ?float $pictureProfileSize = null;

    #[ORM\Column(nullable: true)]
    private ?string $pictureProfileName = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'pictureProfileName' => $this->pictureProfileName,
            'pictureProfileSize' => $this->pictureProfileSize,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->user = $data['user'];
        $this->pictureProfileName = $data['pictureProfileName'];
        $this->pictureProfileSize = $data['pictureProfileSize'];
        $this->createdAt = $data['createdAt'];
        $this->updatedAt = $data['updatedAt'];
        $this->deletedAt = $data['deletedAt'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserBackend
    {
        return $this->user;
    }

    public function setUser(UserBackend $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPictureProfileFile(): ?File
    {
        return $this->pictureProfileFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $pictureProfileFile
     * @return UserBackendInformation
     */
    public function setPictureProfileFile(?File $pictureProfileFile = null): static
    {
        $this->pictureProfileFile = $pictureProfileFile;

        if (null !== $pictureProfileFile) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getPictureProfileName(): ?string
    {
        return $this->pictureProfileName;
    }

    public function setPictureProfileName(?string $pictureProfileName): static
    {
        $this->pictureProfileName = $pictureProfileName;

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

    public function getPictureProfileSize(): ?float
    {
        // Convert this size from Kb to Mo
        $this->pictureProfileSize = $this->pictureProfileSize / 1000;

        return $this->pictureProfileSize;
    }

    public function setPictureProfileSize(?float $pictureProfileSize): static
    {
        $this->pictureProfileSize = $pictureProfileSize;

        return $this;
    }
}
