<?php

namespace AppAdmin\Form;

use App\Form\AbstractForm;
use App\Form\FormInterface;
use Zend\Form;

abstract class AbstractEdit extends AbstractForm implements FormInterface
{
    public function init()
    {
        $this->add([
            'name' => 'save',
            'type' => Form\Element\Button::class,
            'options' => [
                'label' => _('Save changes'),
            ],
            'attributes' => [
                'type' => 'submit',
            ],
        ]);

        $this->add([
            'name' => 'back',
            'type' => Form\Element\Button::class,
            'options' => [
                'label' => _('Back'),
            ],
            'attributes' => [
                'type' => 'button',
                'onclick' => 'app.goBack()',
            ],
        ]);
    }

    
    /**
     * @var string
     */
    protected $filePath = 'public/images';


    /**
     * Подготавливает файл
     *
     * @param \App\Entity\EntityInterface $entity
     * @param array $fileInfo
     * @param bool $initPath
     */
    public function prepareFile(\App\Entity\EntityInterface $entity, array $fileInfo, bool $initPath = true)
    {
        if ($initPath) {
            $ext = substr($fileInfo['name'], strrpos($fileInfo['name'], '.'));
            $fileInfo['name'] = uniqid('file_') . $ext;

            $parts = explode('\\', get_class($entity));
            $paths = [getcwd(), $this->filePath, crc32(strtolower(array_pop($parts) . time()))];

            $this->serviceManager->get('doctrine_extensions.gedmo.uploadable')->setDefaultPath(
                implode(DIRECTORY_SEPARATOR, $paths)
            );
        }

        $this->serviceManager->get('doctrine_extensions.gedmo.uploadable')->addEntityFileInfo($entity, $fileInfo);
    }
}
