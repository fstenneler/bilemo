<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"product:read"}},
 *     collectionOperations={
 *         "get"
 *     },
 *     itemOperations={
 *         "get"
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100)
     */
    private $sku;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $gencode;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="float")
     */
    private $publicPrice;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dimensions;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $weight;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $os;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $display;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $processor;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $camera;

    /**
     * @Groups({"product:read"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sensors;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Brand")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @Groups({"product:read"})
     */
    private $brandName;

    /**
     * @Groups({"product:read"})
     */
    private $brandLogo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @Groups({"product:read"})
     */
    private $categoryName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Color")
     * @ORM\JoinColumn(nullable=false)
     */
    private $color;

    /**
     * @Groups({"product:read"})
     */
    private $colorName;

    /**
     * @Groups({"product:read"})
     */
    private $colorHexa;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="product", orphanRemoval=true)
     */
    private $media;

    /**
     * @Groups({"product:read"})
     */
    private $mediaUrl;

    public function __construct()
    {
        $this->media = new ArrayCollection();
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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getGencode(): ?string
    {
        return $this->gencode;
    }

    public function setGencode(?string $gencode): self
    {
        $this->gencode = $gencode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublicPrice(): ?float
    {
        return $this->publicPrice;
    }

    public function setPublicPrice(float $publicPrice): self
    {
        $this->publicPrice = $publicPrice;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function setDimensions(?string $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getOs(): ?string
    {
        return $this->os;
    }

    public function setOs(?string $os): self
    {
        $this->os = $os;

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(?string $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    public function setProcessor(?string $processor): self
    {
        $this->processor = $processor;

        return $this;
    }

    public function getCamera(): ?string
    {
        return $this->camera;
    }

    public function setCamera(?string $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    public function getSensors(): ?string
    {
        return $this->sensors;
    }

    public function setSensors(?string $sensors): self
    {
        $this->sensors = $sensors;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrandName(): ?string
    {
        return $this->brandName = $this->brand->getName();
    }

    public function getBrandLogo(): ?string
    {
        return $this->brand->getLogo();
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->category->getName();
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColorName(): ?string
    {
        return $this->color->getName();
    }

    public function getColorHexa(): ?string
    {
        return $this->color->getHexa();
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function getMediaUrl(): ?array
    {   
        $mediaUrl = [];
        foreach($this->media as $media) {
            $mediaUrl[] = $media->getUrl();
        }
        return $mediaUrl;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setProduct($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // set the owning side to null (unless already changed)
            if ($medium->getProduct() === $this) {
                $medium->setProduct(null);
            }
        }

        return $this;
    }
}
