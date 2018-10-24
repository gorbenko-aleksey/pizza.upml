<?php

namespace Application\View\Helper;

use App\Entity\EntityInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Exception\InvalidArgumentException;

class CreatePreviewSrc extends AbstractHelper
{
    /**
     * @param EntityInterface $entity
     * @param int|null $width
     * @param int|null $height
     *
     * @return string
     */
    public function __invoke(EntityInterface $entity, int $width = null, int $height = null) : string
    {
        if (!$width && !$height) {
            throw new InvalidArgumentException("Both values can not be null");
        }

        $parts = explode(DIRECTORY_SEPARATOR, $entity->getPath());
        $name = $width . '_' . $height . '_' . end($parts);
        $parts[count($parts) - 1] = $name;

        return str_replace(getcwd() . '/public', '', implode('/', $parts));
    }
}
