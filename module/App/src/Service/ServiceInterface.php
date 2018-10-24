<?php

namespace App\Service;

use App\Mapper\RepositoryInterface;

interface ServiceInterface
{
    const EVENT_BEFORE_SAVE = 'beforeSave';
    const EVENT_AFTER_SAVE = 'afterSave';

    const EVENT_BEFORE_DELETE = 'beforeDelete';
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * Get mapper
     *
     * @return RepositoryInterface
     */
    public function getRepository();
}
