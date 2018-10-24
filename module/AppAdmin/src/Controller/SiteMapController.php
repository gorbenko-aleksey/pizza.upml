<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\SiteMap as SiteMapForm;
use Application\Entity;
use Application\Repository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Model\ViewModel;

/**
 * Class SiteMapController
 *
 * @package AppAdmin\Controller
 *
 * @method Entity\User identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class SiteMapController extends AbstractActionController
{
    /**
     * @var FormProvider
     */
    protected $formProvider;

    /**
     * @var Repository\SiteMap
     */
    protected $repository;

    /**
     * SiteMapController constructor.
     *
     * @param EntityManager $em
     * @param FormProvider $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->repository = $em->getRepository(Entity\SiteMap::class);
        $this->formProvider = $formProvider;
    }

    public function editAction()
    {
        /** @var SiteMapForm\Edit $form */
        $form = $this->formProvider->provide(SiteMapForm\Edit::class);

        $siteMap = $this->repository->findOneBy([]);
        $form->bind($siteMap);

        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $this->repository->save($siteMap);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Sitemap.xml has been saved.'));

                return $this->redirect()->refresh();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }
}
