<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Message text is required")]
    #[Assert\Length(min: 3, max: 255, minMessage: "Message text must be at least 3 characters")]

    private ?string $text = null;

    #[ORM\Column(type: "uuid", unique: true, nullable: false)]
    private ?Uuid $uuid = null;

    public function __construct()
    {
        // Generate a new UUID
        $this->uuid = Uuid::v7();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

}
