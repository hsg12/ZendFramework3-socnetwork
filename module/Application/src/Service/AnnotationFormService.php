<?php

namespace Application\Service;

use Application\Service\AnnotationFormServiceInterface;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class AnnotationFormService implements AnnotationFormServiceInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAnnotationForm($formObj)
    {
        $builder = new AnnotationBuilder($this->entityManager);
        $form = $builder->createForm($formObj);
        $form->setHydrator(new DoctrineObject($this->entityManager));
        $form->bind($formObj);

        return $form ? $form : false;
    }
}
