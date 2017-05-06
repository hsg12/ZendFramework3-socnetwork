<?php

namespace Application\Controller;

use Application\Entity\Relationship;
use Application\Entity\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\EntityManagerInterface;
use Zend\View\Model\ViewModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Authentication\Form\UpdateForm;

class ProfileController extends AbstractActionController
{
    private $entityManager;
    private $updateForm;
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UpdateForm $updateForm
    ) {
        $this->entityManager = $entityManager;
        $this->updateForm    = $updateForm;
        $this->repository    = $this->entityManager->getRepository(User::class);
    }

    public function indexAction()
    {
        $username = $this->params()->fromRoute('username');
        $username = $this->clearString($username);
        $user = $this->repository->findOneBy(['username' => $username]);

        if (! $user) {
            return $this->notFoundAction();
        }

        $friends = $this->getUserFriends($this->entityManager, $user);

        // request information for identified user  ///////////////////////////

        $pendingAnswerIdentityUser = $this->entityManager
                              ->getRepository(Relationship::class)
                              ->pendingAnswer($this->identity()->getId(), $user->getId());

        // request information for not identified user  ///////////////////////

        $pendingAnswerUser = $this->entityManager
                                  ->getRepository(Relationship::class)
                                  ->pendingAnswer($user->getId(), $this->identity()->getId());

        ///////////////////////////////////////////////////////////////////////

        $isFriends = $this->entityManager
                              ->getRepository(Relationship::class)
                              ->checkingFriendship($this->identity()->getId(), $user->getId());

        ///////////////////////////////////////////////////////////////////////

        return new ViewModel([
            'user'          => $user,
            'friends'       => $friends,
            'pendingAnswerIdentityUser' => $pendingAnswerIdentityUser,
            'pendingAnswerUser' => $pendingAnswerUser,
            'isFriends'     => $isFriends,
        ]);
    }

    public function editAction()
    {
        $username = $this->params()->fromRoute('username');
        $username = $this->clearString($username);
        $user = $this->repository->findOneBy(['username' => $username]);

        if (! $user) {
            return $this->notFoundAction();
        }

        $form = $this->updateForm;

        $form->setHydrator(new DoctrineObject($this->entityManager));
        $form->bind($user);

        $request = $this->getRequest();
        if ($request->isPost()) {

            /* In order to have the choice, to change or not to change password  */
            $postArray = $request->getPost()->toArray();

            if (strlen($postArray['password']) > 0) {
                $form->setValidationGroup(['firstName', 'lastName', 'password', 'location']);
            } else {
                $form->setValidationGroup(['firstName', 'lastName', 'location']);
            }
            /* End block */

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user = $form->getData();

                /* In order, to not work, when an empty password  */
                if (strlen($postArray['password']) >= 2) {
                    $this->prepareData($user);
                }
                /* End block */

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Profile edited');
                $this->redirect()->toRoute('profile', ['username' => $user->getUsername()]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'user' => $user,
        ]);
    }

    private function prepareData($user)
    {
        $user->setPasswordSalt(sha1(time() . 'userPasswordSalt'));
        $user->setPassword(sha1('passwordStaticSalt' . $user->getPassword() . $user->getPasswordSalt()));
    }
}
