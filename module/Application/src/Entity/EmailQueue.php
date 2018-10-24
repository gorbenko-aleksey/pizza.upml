<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * EmailQueue
 *
 * @ORM\Table(name="email_queue")
 * @ORM\Entity(repositoryClass="Application\Repository\EmailQueue")
 */
class EmailQueue extends Entity\AbstractEntity
{
    use Entity\Property\Id;
    use Entity\Property\CreatedAt;
    use Entity\Property\ChangedAt;

    /**
     * @var int
     */
    const LOWER_PRIORITY = -100;

    /**
     * @var int
     */
    const MEDIUM_PRIORITY = 0;

    /**
     * @var int
     */
    const HIGHER_PRIORITY = 100;

    /**
     * @var Email
     *
     * @ORM\Column(name="message", type="object", nullable=false, options={"collation":"utf8mb4_unicode_ci"})
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="exception_trace", type="text", length=65535, nullable=true)
     */
    private $exceptionTrace;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="smallint")
     */
    private $priority;

    public function __construct()
    {
        $this->priority = self::MEDIUM_PRIORITY;
    }

    /**
     * @param Email $message
     *
     * @return EmailQueue
     */
    public function setMessage(Email $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Email
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $exceptionTrace
     *
     * @return EmailQueue
     */
    public function setExceptionTrace($exceptionTrace)
    {
        $this->exceptionTrace = $exceptionTrace;

        return $this;
    }

    /**
     * @return string
     */
    public function getExceptionTrace()
    {
        return $this->exceptionTrace;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return self
     */
    public function setPriority($priority)
    {
        if ($priority > self::HIGHER_PRIORITY) {
            $priority = self::HIGHER_PRIORITY;
        } else {
            if ($priority < self::LOWER_PRIORITY) {
                $priority = self::LOWER_PRIORITY;

            }
        }

        $this->priority = $priority;

        return $this;
    }
}
