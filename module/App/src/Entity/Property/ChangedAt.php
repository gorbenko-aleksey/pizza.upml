<?php

namespace App\Entity\Property;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait ChangedAt
{
    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="changed_at", type="datetime")
     */
    private $changedAt;

    /**
     * @return DateTime
     */
    public function getChangedAt()
    {
        return $this->changedAt;
    }

    /**
     * @param DateTime $changedAt
     *
     * @return $this
     */
    public function setChangedAt(DateTime $changedAt)
    {
        $this->changedAt = $changedAt;

        return $this;
    }
}
