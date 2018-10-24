<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity;

/**
 * Product
 *
 * @ORM\Table(name="product", uniqueConstraints={@ORM\UniqueConstraint(name="product_code", columns={"code"})})
 * @ORM\Entity(repositoryClass="Application\Repository\Product")
 */
class Product extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * Не активен
     */
    const STATUS_DISABLED = 0;

    /**
     * Активен
     */
    const STATUS_ENABLED = 1;

    /**
     * @var \Application\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category;

    /**
     * @var \Application\Entity\Receipt
     *
     * @ORM\ManyToOne(targetEntity="Receipt", inversedBy="products")
     * @ORM\JoinColumn(name="receipt_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $receipt;

    /**
     * @var CategoryImage
     *
     * @ORM\OneToOne(targetEntity="ProductImage", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    protected $image;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string")
     * @Gedmo\Slug(fields={"code"}, unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="full_description", type="text", nullable=true)
     */
    private $fullDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="html_title", type="string", nullable=true)
     */
    private $htmlTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="string", nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", nullable=true)
     */
    private $metaDescription;

    
    /**
     * @param Category $category
     *
     * @return Product
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Receipt $receipt
     *
     * @return Product
     */
    public function setReceipt(Receipt $receipt = null)
    {
        $this->receipt = $receipt;

        return $this;
    }

    /**
     * @return Receipt|null
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * Get all products categories
     *
     * @return ArrayCollection
     */
    public function getCategories()
    {
        $categories = new ArrayCollection();
        $category = $this->getCategory();

        if (!$category) {
            return $categories;
        }

        $categories->add($category);

        while ($category = $category->getParent()) {
            if ($category->getLevel() === 0) {
                break;
            }

            $categories->add($category);
        }

        return $categories;
    }

    /**
     * @return ProductImage|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ProductImage $image
     *
     * @return $this
     */
    public function setImage(ProductImage $image = null)
    {
        if ($image !== null) {
            $image->setProduct($this);
        }

        $this->image = $image;

        return $this;
    }

    /**
     * @param boolean $status
     *
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Product
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return Product
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set fullDescription
     *
     * @param string $fullDescription
     *
     * @return Product
     */
    public function setFullDescription($fullDescription)
    {
        $this->fullDescription = $fullDescription;

        return $this;
    }

    /**
     * Get fullDescription
     *
     * @return string|null
     */
    public function getFullDescription()
    {
        return $this->fullDescription;
    }

    /**
     * Set htmlTitle
     *
     * @param string $htmlTitle
     *
     * @return Product
     */
    public function setHtmlTitle($htmlTitle)
    {
        $this->htmlTitle = $htmlTitle;

        return $this;
    }

    /**
     * Get htmlTitle
     *
     * @return string|null
     */
    public function getHtmlTitle()
    {
        return $this->htmlTitle;
    }
    
    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     *
     * @return Product
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string|null
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Product
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }
}
