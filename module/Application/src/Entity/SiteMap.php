<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * SiteMap
 *
 * @ORM\Table(name="site_map")
 * @ORM\Entity(repositoryClass="Application\Repository\SiteMap")
 */
class SiteMap extends Entity\AbstractEntity implements Property\ChangerInterface
{
    use Entity\Property\Id;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}
