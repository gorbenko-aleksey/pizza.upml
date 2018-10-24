<?php

namespace AppAdmin\Controller;

use Zend\Console\ColorInterface;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Application\Service\EmailQueue as EmailQueueService;

class ConsoleController extends AbstractConsoleController
{
    /**
     * @var EmailQueueService
     */
    private $emailQueueService;

    /**
     * Constructor
     *
     * @param EmailQueueService $emailQueueService
     */
    public function __construct(EmailQueueService $emailQueueService)
    {
        $this->emailQueueService = $emailQueueService;
    }

    public function sendTopAction()
    {
        if (($countError = $this->emailQueueService->sendTop())) {
            $this->getConsole()->writeLine($countError . ' letters have not been sent!', ColorInterface::RED);
        }
    }
}
