<?php

namespace AppAdmin\Controller;

use App\Provider\Form as FormProvider;
use AppAdmin\Form\Seo\Edit as BaseEditForm;
use AppAdmin\Form\Seo\Filter as FilterForm;
use AppAdmin\Form\Sorter as SorterForm;
use Application\Entity\Seo as SeoEntity;
use Application\Repository\Seo as SeoRepository;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Model\ViewModel;

/**
 * Class SeoController
 *
 * @package AppAdmin\Controller
 *
 * @method UserEntity identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class SeoController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormProvider
     */
    protected $formProvider;

    /**
     * UserController constructor.
     *
     * @param EntityManager $em
     * @param FormProvider $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->em = $em;
        $this->formProvider = $formProvider;
    }

    public function indexAction()
    {
        /** @var FilterForm $filter */
        $filter = $this->formProvider->provide(FilterForm::class);
        $filter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        /** @var SorterForm $filter */
        $sorter = $this->formProvider->provide(SorterForm::class);
        $sorter->setData($this->getRequest()->getQuery()->toArray())->isValid();

        $parameters = array_merge($filter->prepareAndGetData(), $sorter->prepareAndGetData());
        $rules = $this->getRepository()->findAllWithParameters($parameters);

        return new ViewModel([
            'rules' => $rules,
            'filter' => $filter,
        ]);
    }

    public function editAction()
    {
        $id = (int) $this->params('id');
        $form = $this->formProvider->provide(BaseEditForm::class);

        if ($id) {
            $seo = $this->getRepository()->find($id);
        } else {
            $seo = new SeoEntity();
        }

        if (!$seo) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Seo was not found.'));

            return $this->comeBack();
        }

        $form->bind($seo);

        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $this->getRepository()->save($seo);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Seo has been saved.'));

                return $this->comeBack();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params('id');
        $seo = $this->getRepository()->find($id);

        if (!$seo) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Seo was not found.'));

            return $this->comeBack();
        }

        $this->getRepository()->remove($seo);

        $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Seo has been deleted.'));

        return $this->comeBack();
    }

    public function changePositionAction()
    {
        $id = (int) $this->params('id');
        $position = $this->params('position', null);

        if ($position === null) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('New position can not be empty.'));

            return $this->comeBack();
        }

        $position = (int) $position;
        $seo = $this->getRepository()->find($id);

        if (!$seo) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Seo was not found.'));

            return $this->comeBack();
        }

        $seo->setSort($position);

        $this->getRepository()->save($seo);

        return $this->comeBack();
    }

    /**
     * @return SeoRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(SeoEntity::class);
    }
}
