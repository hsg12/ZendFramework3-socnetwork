<?php

namespace Application\Controller\Plugin;

use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Doctrine\ORM\EntityManagerInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetAnnotationForm extends AbstractPlugin
{
    public function __invoke(EntityManagerInterface $entityManager, $formObj)
    {
        $builder = new AnnotationBuilder($entityManager);
        $form = $builder->createForm($formObj);
        $form->setHydrator(new DoctrineObject($entityManager));
        $form->bind($formObj);

        return $form ? $form : false;
    }
}
