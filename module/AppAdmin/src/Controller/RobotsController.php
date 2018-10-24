<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\Robots as RobotsForm;
use Application\Entity;
use Application\Repository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Model\ViewModel;

/**
 * Class RobotsController
 *
 * @package AppAdmin\Controller
 *
 * @method Entity\User identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class RobotsController extends AbstractActionController
{
    /**
     * @var FormProvider
     */
    protected $formProvider;

    /**
     * @var Repository\Robots
     */
    protected $repository;

    /**
     * RobotsController constructor.
     *
     * @param EntityManager $em
     * @param FormProvider $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->repository = $em->getRepository(Entity\Robots::class);
        $this->formProvider = $formProvider;
    }

    public function editAction()
    {
        /** @var RobotsForm\Edit $form */
        $form = $this->formProvider->provide(RobotsForm\Edit::class);

        $robots = $this->repository->findOneBy([]);
        $form->bind($robots);

        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $this->repository->save($robots);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Robots.txt has been saved.'));

                return $this->redirect()->refresh();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }
}
