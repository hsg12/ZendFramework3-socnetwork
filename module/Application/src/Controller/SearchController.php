<?php

namespace Application\Controller;

use Application\Entity\User;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\EntityManagerInterface;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository(User::class);
    }

    private function clearString($str)
    {
        $stripTagsFilter = new StripTags();
        $str = $stripTagsFilter->filter($str);

        $stringTrimFilter = new StringTrim();
        $str = $stringTrimFilter->filter($str);

        return $str;
    }

    public function indexAction()
    {
        $users = false;
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $search = $request->getPost('searchPeople');
        $search = $this->clearString($search);

        if (! empty($search)) {
            $users = $this->repository->search($search);
        }

        return new ViewModel([
            'search' => $search,
            'users'  => $users,
        ]);
    }
}
