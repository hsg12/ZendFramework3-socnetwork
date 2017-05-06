<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\User;

class UserRepository extends EntityRepository
{
    public function login(User $user, $authService)
    {
        $adapter = $authService->getAdapter();
        $adapter->setIdentity($user->getUsername());
        $adapter->setCredential($user->getPassword());
        $authResult = $authService->authenticate();

        if ($authResult->isValid()) {
            $identity = $authResult->getIdentity();
            $authService->getStorage()->write($identity);
        }

        return $authResult;
    }

    public function search($value)
    {
        $value = '%' . $value . '%';

        $sql  = 'SELECT u ';
        $sql .= 'FROM ' . User::class . ' AS u ';
        $sql .= 'WHERE CONCAT(u.firstName, \' \', u.lastName) ';
        $sql .= 'LIKE :value ';
        $sql .= 'OR u.username ';
        $sql .= 'LIKE :value';

        $query = $this->getEntityManager()->createQuery($sql)->setParameter('value', $value);
        $result = $query->getResult();

        return $result ? $result : false;
    }
}
