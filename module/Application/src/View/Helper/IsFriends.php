<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Relationship;

class IsFriends extends AbstractHelper
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository(Relationship::class);
    }

    public function __invoke($userId, $friendId)
    {
        $result = $this->repository->checkingFriendship($userId, $friendId);
        return $result ? true : false;
    }
}
