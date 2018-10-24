<?php

namespace Application\Service;

use Application\Entity;
use Application\Repository;
use Zend\Mail;
use Zend\Mime;
use Doctrine\ORM\EntityManager;
use App\Service\IdnConverter;

class EmailQueue
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var IdnConverter
     */
    protected $idnConverter;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Repository\EmailQueue
     */
    protected $emailQueueRepository;

    /**
     * @var Repository\EmailQueueLog
     */
    protected $emailQueueLogRepository;

    /**
     * EmailQueue constructor.
     *
     * @param EntityManager $em
     * @param IdnConverter $idnConverter
     * @param array $config
     */
    public function __construct(EntityManager $em, IdnConverter $idnConverter, array $config)
    {
        $this->em = $em;
        $this->idnConverter = $idnConverter;
        $this->config = $config;

        $this->emailQueueRepository = $this->em->getRepository(Entity\EmailQueue::class);
        $this->emailQueueLogRepository = $this->em->getRepository(Entity\EmailQueueLog::class);
    }

    /**
     * Get email config
     *
     * @return array
     */
    protected function getConfig()
    {
        return @$this->config['email'];
    }

    /**
     * If set maintenance mode now
     *
     * @return bool
     */
    protected function isMaintenanceMode()
    {
        $config = $this->getConfig();

        return @$config['maintenance'];
    }

    /**
     * Add email to queue
     *
     * @param Entity\EmailQueue $email
     */
    public function add(Entity\EmailQueue $email)
    {
        $this->emailQueueRepository->save($email);
    }

    /**
     * Send email and save him to log
     *
     * @param Entity\EmailQueue $email
     */
    protected function send(Entity\EmailQueue $email)
    {
        $newAddressList = new Mail\AddressList();
        $message = $email->getMessage();

        foreach ($message->getTo() as $address) {
            $newAddressList->add(
                $this->idnConverter->encode($address->getEmail()),
                $address->getName()
            );
        }

        $message->setTo($newAddressList);

        // send email
        $transport = new Mail\Transport\Sendmail();
        $transport->send($email->getMessage());

        // save sended message to log
        $emailLog = new Entity\EmailQueueLog();
        $emailLog->setMessage($email->getMessage()->toString());

        $this->emailQueueLogRepository->save($emailLog);
    }

    /**
     * Send emails from queue
     *
     * @return int
     */
    public function sendTop()
    {
        try {
            $config = $this->getConfig();
            $limit = $config['sendTopLimit'];
        } catch (\Exception $e) {
            $limit = null;
        }

        $errors = 0;

        try {
            if (!$this->isMaintenanceMode()) {
                /** @var Entity\EmailQueue[] $emails */
                $emails = $this->emailQueueRepository->findTop($limit);

                foreach ($emails as $email) {
                    try {
                        $this->send($email);

                        $this->emailQueueRepository->remove($email);
                    } catch (\Exception $e) {
                        $email->setExceptionTrace($e);
                        $this->sendErrorReport(__METHOD__, $e->getMessage());

                        $this->emailQueueRepository->save($email);

                        $errors++;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->sendErrorReport(__METHOD__, $e->getMessage());
        }

        return $errors;
    }

    /**
     * Send email immediately
     *
     * @param Entity\EmailQueue $email
     *
     * @return bool
     */
    public function sendImmediately(Entity\EmailQueue $email)
    {
        $success = false;

        try {
            if (!$this->isMaintenanceMode()) {
                $this->send($email);
            } else {
                $this->add($email);
            }

            $success = true;
        } catch (\Exception $e) {
            $email->setExceptionTrace($e->getTraceAsString());

            $this->emailQueueRepository->save($email);
        }

        return $success;
    }

    /**
     * Send error report
     *
     * @param string $subject
     * @param string $body
     */
    public function sendErrorReport($subject, $body)
    {
        $config = $this->getConfig();
        $from = @$config['noreply'];
        $to = @$config['admins'];

        if (!$from || !$to) {
            return;
        }

        $emailQueue = $this->createMail($subject, $body, $to, [$from['email'] => $from['name']]);

        $this->sendImmediately($emailQueue);
    }

    /**
     * Send message use queue
     *
     * @param string $body
     * @param string $subject
     * @param array|null $from
     * @param array $to
     * @param bool|false $addToQueue
     *
     * @return self
     */
    public function sendMail($body, $subject, array $from = null, array $to, $addToQueue = false)
    {
        $emailQueue = $this->createMail($body, $subject, $from, $to);

        if ($addToQueue) {
            $this->add($emailQueue);
        } else {
            $this->sendImmediately($emailQueue);
        }

        return $this;
    }

    /**
     * Create mail
     *
     * @param string $body
     * @param string $subject
     * @param array|null $from
     * @param array $to
     * @param int $priority
     *
     * @return Entity\EmailQueue
     */
    protected function createMail($body, $subject, $from = null, $to, $priority = null)
    {
        $from = $from ? $from : $this->getDefaultSenderAddress();

        $email = new Entity\Email();

        $html = new Mime\Part($body);
        $html->type = "text/html";
        $html->charset = 'utf-8';
        $html->encoding = Mime\Mime::ENCODING_QUOTEDPRINTABLE;

        $body = new Mime\Message();
        $body->setParts([$html]);

        $email->setTo($to)->setFrom($from);
        $email->setSubject($subject)->setBody($body);

        $emailQueue = new Entity\EmailQueue();
        $emailQueue->setMessage($email);

        if ($priority) {
            $emailQueue->setPriority($priority);
        }

        return $emailQueue;
    }

    /**
     * Get default sender
     *
     * @return array
     */
    protected function getDefaultSenderAddress()
    {
        $config = $this->getConfig();

        return [$config['noreply']['email'] => $config['noreply']['name']];
    }
}
