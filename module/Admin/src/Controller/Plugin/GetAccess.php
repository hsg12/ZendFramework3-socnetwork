<?php

namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Application\Entity\User;
use Zend\Authentication\AuthenticationServiceInterface;

class GetAccess extends AbstractPlugin
{
    private $authService;

    public function __construct(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke()
    {
        $user = $this->authService->getIdentity();
        $role = $user->getRole();

        if ($role && ($role === 'superadmin' || $role === 'admin')) {
            return true;
        }

        exit('Access denied');
    }
}
