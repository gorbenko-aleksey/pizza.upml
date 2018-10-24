<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Application\Repository\Page")
 */
class Page extends Entity\AbstractEntity implements
    Entity\EntitySeoRulesInterface,
    Entity\EntityCodeInterface,
    Property\CreatorInterface,
    Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * Not active
     */
    const STATUS_NOT_ACTIVE = 0;

    /**
     * Active
     */
    const STATUS_ACTIVE = 1;

    /**
     * List of page drivers
     */
    const DRIVERS = [
        'about'   => 'about',
        'default' => 'default',
        'contact' => 'contact',
    ];

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    protected $status;

    /**
     * @ORM\Column(name="name", type="text", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="code", type="text", length=255, nullable=true)
     * @Gedmo\Slug(fields={"code"}, unique=true)
     */
    protected $code;

    /**
     * @ORM\Column(name="driver", type="text", length=255, nullable=true)
     */
    protected $driver;

    /**
     * @ORM\Column(name="short_description", type="text", length=255, nullable=true)
     */
    protected $shortDescription;

    /**
     * @ORM\Column(name="full_description", type="text", length=4294967295, nullable=true)
     */
    protected $fullDescription;

    /**
     * @ORM\Column(name="html_title", type="text", length=1000, nullable=true)
     */
    protected $htmlTitle;

    /**
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    protected $metaDescription;

    /**
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    protected $metaKeywords;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     *
     * @return $this
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     *
     * @return $this
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullDescription()
    {
        return $this->fullDescription;
    }

    /**
     * @param string $fullDescription
     *
     * @return $this
     */
    public function setFullDescription($fullDescription)
    {
        $this->fullDescription = $fullDescription;

        return $this;
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
     *
     * @return $this
     */
    public function setHtmlTitle($htmlTitle)
    {
        $this->htmlTitle = $htmlTitle;

        return $this;
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
     *
     * @return $this
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
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
     *
     * @return $this
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDriverEnable()
    {
        return in_array($this->getDriver(), self::DRIVERS, true);
    }
}
