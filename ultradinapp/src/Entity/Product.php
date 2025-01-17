<?php
namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_product", type: "integer")]
    #[Groups(['product:read'])]
    private ?int $id_product = null;

    #[Groups(['product:read'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['product:read'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(['product:read'])]
    #[ORM\Column(length: 320)]
    private ?string $image_url = null;

    #[Groups(['product:read'])]
    #[ORM\Column]
    private ?int $stock = null;

    #[Groups(['product:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[Groups(['product:read'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $technical_features = null;


    #[Groups(['product:read'])]
    #[ORM\Column]
    private ?bool $availability = null;

    #[Groups(['product:read'])]
    #[ORM\Column]
    private ?float $price = null;

    #[Groups(['product:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_updated = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: 'product_category')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id_product')]
    #[ORM\InverseJoinColumn(name: 'category_id', referencedColumnName: 'id_category')]
    #[MaxDepth(1)]
    #[Groups(['product:read'])]
    private Collection $category;

    #[Groups(['product:read'])]
    #[ORM\Column]
    private ?float $price_year = null;

    #[ORM\Column]
    private ?int $weekly_sales = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getIdProduct(): ?int
    {
        return $this->id_product;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getTechnicalFeatures(): ?string
    {
        return $this->technical_features;
    }

    public function setTechnicalFeatures(string $technical_features): static
    {
        $this->technical_features = $technical_features;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->date_updated;
    }

    public function setDateUpdated(?\DateTimeInterface $date_updated): static
    {
        $this->date_updated = $date_updated;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getPriceYear(): ?float
    {
        return $this->price_year;
    }

    public function setPriceYear(float $price_year): static
    {
        $this->price_year = $price_year;

        return $this;
    }

    public function getWeeklySales(): ?int
    {
        return $this->weekly_sales;
    }

    public function setWeeklySales(int $weekly_sales): static
    {
        $this->weekly_sales = $weekly_sales;

        return $this;
    }

    
}
