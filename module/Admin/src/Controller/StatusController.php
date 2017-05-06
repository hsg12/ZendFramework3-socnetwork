<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Status;

class StatusController extends AbstractActionController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Status::class);
    }

    public function indexAction()
    {
        $searchResult = false;
        $message = '';
        $search  = '';
        $request = $this->getRequest();

        if ($request->isPost()) {
            $search = $request->getPost('search');
            $search = $this->clearString($search);

            if (! empty($search)) {
                $searchResult = $this->repository->search($search);
            }

            if (empty($search) || $searchResult == false) {
                $message = 'No results found, sorry.';
            }
        }

        return new ViewModel([
            'searchResult' => $searchResult,
            'message'      => $message,
            'search'       => $search,
        ]);
    }

    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $status = $this->repository->find($id);

        if (! $this->request->isPost() || ! $id || ! $status) {
            return $this->notFoundAction();
            exit();
        }

        $this->entityManager->remove($status);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Status deleted');
        return $this->redirect()->toRoute('admin/statuses');
    }
}
