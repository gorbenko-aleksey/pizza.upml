<?php

namespace AppAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Repository;
use Application\Entity;
use Doctrine\ORM\EntityManager;
use App\Provider\Form as FormProvider;
use AppAdmin\Form\Income as IncomeForm;
use AppAdmin\View\Helper\RowsPerPage;
use Zend\I18n\Translator\TranslatorInterface;

/**
 * Class IncomeController
 *
 * @package AppAdmin\Controller
 *
 * @method Entity\User identity
 * @method TranslatorInterface translator
 * @method Plugin\ComeBack comeBack
 */
class IncomeController extends AbstractActionController
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
     * IncomeController constructor.
     *
     * @param EntityManager $em
     * @param FormProvider  $formProvider
     */
    public function __construct(EntityManager $em, FormProvider $formProvider)
    {
        $this->em = $em;
        $this->formProvider = $formProvider;
    }

    public function indexAction()
    {
        $offset = (int) $this->getRequest()->getQuery('offset', 1);
        $limit = (int) $this->getRequest()->getQuery('limit', RowsPerPage::LIMIT);

        $paginator = $this->getRepository()->paginatorFetchAll($limit, $offset, []);

        return new ViewModel([
            'paginator' => $paginator,
        ]);
    }

    public function editAction()
    {
        /** @var IncomeForm\Edit $form */
        $form = $this->formProvider->provide(IncomeForm\Edit::class);

        if (($id = (int) $this->params('id'))) {
            $income = $this->getRepository()->find($id);
        } else {
            $income = new Entity\DocumentIncome();
        }

        if (!$income) {
            $this->flashMessenger()->addErrorMessage($this->translator()->translate('Income was not found'));

            return $this->comeBack();
        }

        $form->bind($income);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();

            $form->setData($data);

            if ($form->isValid()) {
                foreach ($income->getIngredients() as $ingredient) {
                    $ingredient->setResidue($ingredient->getWeight());
                }
                $this->getRepository()->save($income);

                $this->flashMessenger()->addSuccessMessage($this->translator()->translate('Income has been saved'));

                return $this->comeBack();
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * @return Repository\DocumentIncome
     */
    protected function getRepository()
    {
        return $this->em->getRepository(Entity\DocumentIncome::class);
    }
}
