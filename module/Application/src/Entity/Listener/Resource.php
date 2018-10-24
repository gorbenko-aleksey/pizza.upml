<?php

namespace Application\Entity\Listener;

use App\Entity\EntityInterface;

class Resource
{
    /**
     * @param EntityInterface $entity
     */
    public function postRemove(EntityInterface $entity)
    {
        $directory = str_replace($entity->getName(), '', $entity->getPath());

        if (!(file_exists($directory))) {
            return;
        }

        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            unlink($directory . $file);
        }

        rmdir($directory);
    }
}
