<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"customer:read"}},
 *     denormalizationContext={"groups"={"customer:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"customer:read", "customer:write"})
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email
     * @Assert\Unique
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @Groups({"customer:read", "customer:write"})
     * @ORM\Column(type="string", length=255)
     * @SecurityAssert\UserPassword
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @Groups({"customer:read", "customer:write"})
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $birthday;

    /**
     * @Groups({"customer:read", "customer:write"})
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $phone;

    /**
     * @Groups({"customer:read", "customer:write"})
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @Assert\NotBlank
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomerAddress", mappedBy="customer", orphanRemoval=true)
     */
    private $customerAddresses;

    /**
     * @Groups({"customer:read"})
     */
    private $customerAddressesCollection;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->customerAddresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|CustomerAddress[]
     */
    public function getCustomerAddresses(): Collection
    {
        return $this->customerAddresses;
    }

    public function addCustomerAddress(CustomerAddress $customerAddress): self
    {
        if (!$this->customerAddresses->contains($customerAddress)) {
            $this->customerAddresses[] = $customerAddress;
            $customerAddress->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerAddress(CustomerAddress $customerAddress): self
    {
        if ($this->customerAddresses->contains($customerAddress)) {
            $this->customerAddresses->removeElement($customerAddress);
            // set the owning side to null (unless already changed)
            if ($customerAddress->getCustomer() === $this) {
                $customerAddress->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}
