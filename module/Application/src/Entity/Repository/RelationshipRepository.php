<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Relationship;

class RelationshipRepository extends EntityRepository
{
    public function getFriends($userId)
    {
        $sql  = 'SELECT r ';
        $sql .= 'FROM ' . Relationship::class . ' AS r ';
        $sql .= 'WHERE (r.userOne = :userId OR r.userTwo = :userId) ';
        $sql .= 'AND r.status = 1';

        $query = $this->getEntityManager()->createQuery($sql)->setParameter('userId', $userId);
        $result = $query->getResult();

        return $result ? $result : false;
    }

    public function pendingRequest($userId)
    {
        $sql  = 'SELECT r ';
        $sql .= 'FROM ' . Relationship::class . ' AS r ';
        $sql .= 'WHERE (r.userOne = :userId OR r.userTwo = :userId) ';
        $sql .= 'AND r.status = 0';
        $sql .= 'AND r.actionUser != :userId';

        $query = $this->getEntityManager()->createQuery($sql)->setParameter('userId', $userId);
        $result = $query->getResult();

        return $result ? $result : false;
    }

    public function checkingFriendship($userId, $friendId)
    {
        $sql  = 'SELECT r ';
        $sql .= 'FROM ' . Relationship::class . ' AS r ';
        $sql .= 'WHERE ((r.userOne = :userId AND r.userTwo = :friendId) ';
        $sql .= 'OR (r.userOne = :friendId AND r.userTwo = :userId)) ';
        $sql .= 'AND r.status = 1';

        $query = $this->getEntityManager()->createQuery($sql)->setParameters(['userId' => $userId, 'friendId' => $friendId]);
        $result = $query->getResult();

        return $result ? $result : false;
    }

    public function pendingAnswer($userId, $friendId)
    {
        $sql  = 'SELECT r ';
        $sql .= 'FROM ' . Relationship::class . ' AS r ';
        $sql .= 'WHERE ((r.userOne = :userId AND r.userTwo = :friendId) ';
        $sql .= 'OR (r.userOne = :friendId AND r.userTwo = :userId)) ';
        $sql .= 'AND r.status = 0 ';
        $sql .= 'AND r.actionUser = :userId';

        $query = $this->getEntityManager()->createQuery($sql)->setParameters(['userId' => $userId, 'friendId' => $friendId]);
        $result = $query->getResult();

        return $result ? true : false;
    }
}
