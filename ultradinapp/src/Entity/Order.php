<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_confirmed = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $order_uuid = null;

    #[ORM\Column]
    private ?float $total_price = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $eta = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName:'id_user')]
    private ?user $user = null;

    /**
     * @var Collection<int, product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class)]
    #[ORM\JoinTable(
        name: "order_product", // Name of the join table
        joinColumns: [
            new ORM\JoinColumn(name: "order_id", referencedColumnName: "id") // Column in the `order_product` table referencing `Order`
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: "product_id", referencedColumnName: "id_product") // Column in the `order_product` table referencing `Product`
        ]
    )]

    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateConfirmed(): ?\DateTimeInterface
    {
        return $this->date_confirmed;
    }

    public function setDateConfirmed(\DateTimeInterface $date_confirmed): static
    {
        $this->date_confirmed = $date_confirmed;

        return $this;
    }

    public function getOrderUuid(): ?string
    {
        return $this->order_uuid;
    }

    public function setOrderUuid(string $order_uuid): static
    {
        $this->order_uuid = $order_uuid;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): static
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getEta(): ?\DateTimeInterface
    {
        return $this->eta;
    }

    public function setEta(\DateTimeInterface $eta): static
    {
        $this->eta = $eta;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(product $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }
}
