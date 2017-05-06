<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Relationship;
use Application\Entity\User;

class FriendController extends AbstractActionController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Relationship::class);
    }

    public function indexAction()
    {
        $user = $this->identity();
        $userAllFriends = false;
        $usersRequest   = false;

        $userAllFriends = $this->getUserFriends($this->entityManager, $user);

        $pendingRequest = $this->repository->pendingRequest($user->getId());

        if (is_array($pendingRequest) && ! empty($pendingRequest)) {
            foreach ($pendingRequest as $value) {
                if ($user->getId() == $value->getUserOne()->getId()) {
                    $usersRequest[] = $value->getUserTwo();
                } else {
                    $usersRequest[] = $value->getUserOne();
                }
            }
        }

        return new ViewModel([
            'friends'      => $userAllFriends,
            'usersRequest' => $usersRequest,
        ]);
    }

    public function addAction()
    {
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $username = $this->params()->fromRoute('username');
        $username = $this->clearString($username);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (! $user) {
            return $this->notFoundAction();
        }

        $relationship = new Relationship();
        $relationship->setUserOne($this->identity());
        $relationship->setUserTwo($user);
        $relationship->setActionUser($this->identity());

        $this->entityManager->persist($relationship);
        $this->entityManager->flush();

        return $this->redirect()->toRoute('profile', ['username' => $username]);
    }

    public function acceptAction()
    {
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $username = $this->params()->fromRoute('username');
        $username = $this->clearString($username);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (! $user) {
            return $this->notFoundAction();
        }

        $relationship = $this->repository->findOneBy(['userOne' => $user, 'userTwo' => $this->identity()]);
        $relationship->setActionUser($this->identity());
        $relationship->setStatus(1);

        $this->entityManager->persist($relationship);
        $this->entityManager->flush();

        return $this->redirect()->toRoute('friend');
    }

    public function deleteAction()
    {
        if (! $this->request->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $username = $this->params()->fromRoute('username');
        $username = $this->clearString($username);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (! $user) {
            return $this->notFoundAction();
        }

        $friends = $this->repository->checkingFriendship($this->identity(), $user);

        $this->entityManager->remove($friends[0]);
        $this->entityManager->flush();

        return $this->redirect()->toRoute('friend');
    }
}
