<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\StatusLike;

class StatusLikeRepository extends EntityRepository
{
    public function getLikeCount($status)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(sl.id)');
        $qb->from(StatusLike::class, 'AS sl');
        $qb->where('sl.status = :status');
        $qb->setParameter('status', $status);

        $query = $qb->getQuery();
        $result = $query->getSingleScalarResult();

        return $result ? $result : false;
    }
}
