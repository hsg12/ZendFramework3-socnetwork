<?php

namespace Admin\Controller;

use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\User;
use Authentication\Form\UpdateForm;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class UserController extends AbstractActionController
{
    private $entityManager;
    private $repository;
    private $updateForm;

    public function __construct(
        EntityManagerInterface $entityManager,
        UpdateForm $updateForm
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
        $this->updateForm = $updateForm;
    }

    public function indexAction()
    {
        $admins = $this->repository->findBy(['role' => 'admin']);
        return new ViewModel([
            'admins' => $admins,
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $user = $this->repository->find($id);

        if (! $id || ! $user) {
            return $this->notFoundAction();
        }

        $form = $this->updateForm;
        $form->setHydrator(new DoctrineObject($this->entityManager));
        $form->bind($user);
        $form->setValidationGroup('role');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user = $form->getData();

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('User edited');
                return $this->redirect()->toRoute('admin/users', ['action' => 'edit', 'id' => $id]);
            }
        }

        return new ViewModel([
            'id'   => $id,
            'user' => $user,
            'form' => $form,
        ]);
    }

    public function searchAction()
    {
        $results = [];
        $request = $this->getRequest();
        $response = $this->getResponse();

        if (! $request->isPost()) {
            return $this->notFoundAction();
        } else {
            $search = $request->getPost('admin-search-user');

            if (! empty($search)) {
                $search = $this->clearString($search);
                $users = $this->repository->search($search);

                $result = [];
                $results = [];

                if (is_array($users) && ! empty($users)) {
                    foreach ($users as $user) {
                        $result['id'] = $user->getId();

                        if ($user->getFirstName() && $user->getLastName()) {
                            $result['name'] = $user->getFirstName() . ' ' . $user->getLastName();
                        } else {
                            $result['name'] = $user->getUsername();
                        }

                        $results[] = $result;
                    }
                }
            }
        }

        $response->setContent(Json::encode(['results' => $results]));
        return $response;
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $user = $this->repository->find($id);

        if (! $id || ! $user) {
            return $this->notFoundAction();
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('User deleted');
        return $this->redirect()->toRoute('admin/users');
    }
}
