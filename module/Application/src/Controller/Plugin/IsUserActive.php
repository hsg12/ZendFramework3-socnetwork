<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Application\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class IsUserActive extends AbstractPlugin
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($username)
    {
        $result = true;

        $value = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($value) {
            $result = $this->entityManager->getRepository(User::class)->findOneBy([
                'username' => $username,
                'active'   => '1',
            ]);
        }

        return $result ? $result : false;
    }
}
