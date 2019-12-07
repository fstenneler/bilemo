<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     normalizationContext={"groups": {"user:read"}},
 *     collectionOperations={
 *         "get"
 *     },
 *     itemOperations={
 *         "get"
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ColorRepository")
 */
class Color
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("user:read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     * @Groups("user:read")
     */
    private $hexa;

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHexa(): ?string
    {
        return $this->hexa;
    }

    public function setHexa(?string $hexa): self
    {
        $this->hexa = $hexa;

        return $this;
    }
}
