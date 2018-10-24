<?php

namespace Application\Repository;

use App\Repository\AbstractRepository;

class EmailQueue extends AbstractRepository
{
    /**
     * Find top mails
     *
     * @param int|null $limit
     *
     * @return array
     */
    public function findTop($limit)
    {
        $list = $this->findBy([], ['id' => 'DESC', 'priority' => 'DESC'], $limit, 0);

        return $this->createCollection($list);
    }
}
