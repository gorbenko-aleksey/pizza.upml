<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * EmailQueueLog
 *
 * @ORM\Table(name="email_queue_log")
 * @ORM\Entity(repositoryClass="Application\Repository\EmailQueueLog")
 */
class EmailQueueLog extends Entity\AbstractEntity
{
    use Entity\Property\Id;
    use Entity\Property\CreatedAt;
    use Entity\Property\ChangedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false, options={"collation":"utf8mb4_unicode_ci"})
     */
    private $message;

    /**
     * @param string $message
     *
     * @return EmailQueueLog
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
