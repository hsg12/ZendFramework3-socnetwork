<?php

namespace Admin;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Doctrine\ORM\EntityManager;
use Authentication\Form\UpdateForm;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerPluginConfig()
    {
        return [
            'factories' => [
                'getAccess' => function ($container) {
                    return new Controller\Plugin\GetAccess(
                        $container->get(AuthenticationService::class)
                    );
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\UserController::class => function ($container) {
                    return new Controller\UserController(
                        $container->get(EntityManager::class),
                        $container->get(UpdateForm::class)
                    );
                },
                Controller\StatusController::class => function ($container) {
                    return new Controller\StatusController(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }

    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            __NAMESPACE__,
            'dispatch',
            function ($e) {
                $controller = $e->getTarget();
                $controller->getAccess();
            },
            100
        );

    }
}
