<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Status;

class GetStatusReplay extends AbstractHelper
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository(Status::class);
    }

    public function __invoke($parentId)
    {
        $result = $this->repository->findBy(['parentId' => (int)$parentId]);
        return $result ? $result : false;
    }
}
