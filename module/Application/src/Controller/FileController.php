<?php

namespace Application\Controller;

use App\Service\Preview;
use Zend\ServiceManager\ServiceManager;
use App\Service\Exception\PreviewException;
use Zend\Mvc\Controller\AbstractActionController;

class FileController extends AbstractActionController
{
    /**
     * @var ServiceManager
     */
    public $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function imagesAction()
    {
        $parts   = [];
        $parts[] = 'images';
        $parts[] = $this->params('entity-directory');
        $parts[] = $this->params('file-name');

        $response = $this->getResponse();

        try {
            $this->serviceManager->get(Preview::class)->generate($parts);
        } catch (\Exception $e) {
            if (!($e instanceof PreviewException)) {
                // Тут нужно залогировать сообщение об ошибке
            }

            $response->setStatusCode(404);

            return $response;
        }

        return $this->redirect()->toUrl($this->getRequest()->getUriString());
    }
}
