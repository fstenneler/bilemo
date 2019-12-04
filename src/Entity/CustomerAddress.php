<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
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
     * @ORM\Column(type="string", length=100)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Type(type="string")
     */
    private $business;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $addressLine1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $addressLine2;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $country;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type(type="boolean")
     * @Assert\NotBlank
     */
    private $isDefault;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="customerAddresses")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type(type="integer")
     * @Assert\NotBlank
     */
    private $customer;

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
}
