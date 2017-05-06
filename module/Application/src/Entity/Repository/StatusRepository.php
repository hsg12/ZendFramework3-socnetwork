<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Status;

class StatusRepository extends EntityRepository
{
    public function getStatuses($userId, $ids)
    {
        $sql  = 'SELECT s ';
        $sql .= 'FROM ' . Status::class . ' AS s ';
        $sql .= 'WHERE (';
        $sql .= '(s.parentId IS NULL) AND  ';
        $sql .= '((s.user = :userId) OR (s.user IN (' . $ids . ')))';
        $sql .= ') ';
        $sql .= 'ORDER BY s.id DESC';

        $query = $this->getEntityManager()->createQuery($sql)->setParameter('userId', $userId);

        return $query ? $query : false;
    }

    public function getUserStatuses($userId)
    {
        $sql  = 'SELECT s ';
        $sql .= 'FROM ' . Status::class . ' AS s ';
        $sql .= 'WHERE (';
        $sql .= '(s.parentId IS NULL) AND (s.user = :userId) ';
        $sql .= ') ';
        $sql .= 'ORDER BY s.id DESC';

        $query = $this->getEntityManager()->createQuery($sql)->setParameter('userId', $userId);

        return $query ? $query : false;
    }

    public function search($value)
    {
        $value = '%' . $value . '%';

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('s');
        $qb->from(Status::class, 'AS s');
        $qb->where('s.content LIKE :value');
        $qb->setParameter(':value', $value);

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result ? $result : false;
    }
}
