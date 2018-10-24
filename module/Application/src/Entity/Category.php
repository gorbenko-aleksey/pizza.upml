<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections;
use App\Entity;

/**
 * Category
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="category", uniqueConstraints={@ORM\UniqueConstraint(name="category_code", columns={"code"})}, indexes={@ORM\Index(name="FK_category_category_id", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\Category")
 */
class Category extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * Status active
     */
    const STATUS_ACTIVE = 1;

    /**
     * Status not active
     */
    const STATUS_NOT_ACTIVE = 0;

    /**
     * Ид корневой категории
     */
    const ROOT_ID = 1;

    /**
     * Код корневой категории
     */
    const ROOT_CODE = 'root';

    /**
     * @var integer
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", nullable=false)
     */
    private $left;

    /**
     * @var integer
     *
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", nullable=false)
     */
    private $right;

    /**
     * @var integer
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=false)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Gedmo\Slug(fields={"code"}, unique=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = self::STATUS_NOT_ACTIVE;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=false)
     */
    private $root;

    /**
     * @var string
     *
     * @ORM\Column(name="description_short", type="text", nullable=true)
     */
    private $descriptionShort;

    /**
     * @var string
     *
     * @ORM\Column(name="description_full", type="text", nullable=true)
     */
    private $descriptionFull;

    /**
     * @var \Application\Entity\Category
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="html_title", type="text", nullable=true)
     */
    private $htmlTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    public function __construct()
    {
        $this->products = new Collections\ArrayCollection();
        $this->children = new Collections\ArrayCollection();
    }

    /**
     * Set left
     *
     * @param integer $left
     *
     * @return Category
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Get left
     *
     * @return integer
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set right
     *
     * @param integer $right
     *
     * @return Category
     */
    public function setRight($right)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * Get right
     *
     * @return integer
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Category
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Category
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
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
     * Set parent
     *
     * @param \Application\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\Application\Entity\Category $parent = null)
    {
        if ($this->getCode() != self::ROOT_CODE) {
            $this->parent = $parent;
        }

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Application\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Product $product
     *
     * @return Category
     */
    public function addProduct($product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return Category
     */
    public function removeProduct($product)
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @param Collection $products
     *
     * @return Category
     */
    public function addProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param int $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getDescriptionFull()
    {
        return $this->descriptionFull;
    }

    /**
     * @param string $descriptionFull
     */
    public function setDescriptionFull($descriptionFull)
    {
        $this->descriptionFull = $descriptionFull;
    }

    /**
     * @return string
     */
    public function getHtmlTitle()
    {
        return $this->htmlTitle;
    }

    /**
     * @param string $htmlTitle
     */
    public function setHtmlTitle($htmlTitle)
    {
        $this->htmlTitle = $htmlTitle;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return string
     */
    public function getDescriptionShort()
    {
        return $this->descriptionShort;
    }

    /**
     * @param string $descriptionShort
     */
    public function setDescriptionShort($descriptionShort)
    {
        $this->descriptionShort = $descriptionShort;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Is root category
     *
     * @return bool
     */
    public function isRoot()
    {
        return $this->getLevel() == 0;
    }

    /**
     * Get all children categories
     *
     * @return Collection
     */
    public function getAllChildren()
    {
        return $this->prepareAllChildren($this->getChildren(), new ArrayCollection());
    }

    /**
     * Fill categories array
     *
     * @param Collection $categories
     * @param Collection $allChildrenCategories
     *
     * @return Collection
     */
    protected function prepareAllChildren(Collection $categories, Collection $allChildrenCategories)
    {
        foreach ($categories as $category) {
            $allChildrenCategories->add($category);
            $this->prepareAllChildren($category->getChildren(), $allChildrenCategories);
        }

        return $allChildrenCategories;
    }
}
