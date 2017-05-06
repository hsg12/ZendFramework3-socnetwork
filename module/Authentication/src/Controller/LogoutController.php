<?php

namespace Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Authentication\Model\AppAuthStorage;
use Application\Model\Cart;

class LogoutController extends AbstractActionController
{
    private $ormAuthService;
    private $authStorage;

    public function __construct(
        $ormAuthService,
        AppAuthStorage $authStorage
    ) {
        $this->ormAuthService = $ormAuthService;
        $this->authStorage = $authStorage;
    }

    public function indexAction()
    {
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute('home');
            die;
        }

        $this->authStorage->forgetMe();
        $this->ormAuthService->clearIdentity();

        return $this->redirect()->toRoute('home');
    }
}