<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 *     collectionOperations={
 *         "post"
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CustomerAddressRepository")
 */
class CustomerAddress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $firstName;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The last name cannot be longer than {{ limit }} characters"
     * )
     */
    private $lastName;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The business name cannot be longer than {{ limit }} characters"
     * )
     */
    private $business;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "The line 1 of address cannot be longer than {{ limit }} characters"
     * )
     */
    private $addressLine1;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "The line 2 of address cannot be longer than {{ limit }} characters"
     * )
     */
    private $addressLine2;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "The postal code cannot be longer than {{ limit }} characters"
     * )
     */
    private $postalCode;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The city cannot be longer than {{ limit }} characters"
     * )
     */
    private $city;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "The country cannot be longer than {{ limit }} characters"
     * )
     */
    private $country;

    /**
     * @Groups({"user:read", "user:write"})
     * @ORM\Column(type="boolean")
     * @Assert\Type(
     *     type = "boolean",
     *     message = "This value should be of type {{ type }}"
     * )
     */
    private $isDefault;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="customerAddresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @Groups({"user:write"})
     * @Assert\Type(
     *     type = "integer",
     *     message = "This value should be of type {{ type }}"
     * )
     * @Assert\NotBlank
     */
    private $customerId;

    public function __toString()
    {
        return (string) '[' . $this->id . '] ' . $this->firstName . ' ' . $this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBusiness(): ?string
    {
        return $this->business;
    }

    public function setBusiness(?string $business): self
    {
        $this->business = $business;

        return $this;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function isDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

}
