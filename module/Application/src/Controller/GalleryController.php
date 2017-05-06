<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Relationship;
use Application\Entity\User;
use Application\Entity\Gallery;
use Application\Service\AnnotationFormServiceInterface;

class GalleryController extends AbstractActionController
{
    private $entityManager;
    private $relationshipRepository;
    private $userRepository;
    private $galleryRepository;
    private $annotationFormService;

    public function __construct(
        EntityManagerInterface $entityManager,
        AnnotationFormServiceInterface $annotationFormService
    ) {
        $this->entityManager = $entityManager;
        $this->relationshipRepository = $this->entityManager->getRepository(Relationship::class);
        $this->userRepository = $this->entityManager->getRepository(User::class);
        $this->galleryRepository = $this->entityManager->getRepository(Gallery::class);
        $this->annotationFormService = $annotationFormService;
    }

    public function showAction()
    {
        $username = $this->getEvent()->getRouteMatch()->getParam('username', '');
        $user = $this->userRepository->findOneBy(['username' => $username]);

        $this->checkFriendship($user);

        $images = $this->galleryRepository->findBy(['user' => $user], ['id' => 'DESC']);

        return new ViewModel([
            'user'   => $user,
            'images' => $images,
        ]);

    }

    public function manageAction()
    {
        $form = false;
        $username = $this->getEvent()->getRouteMatch()->getParam('username', '');
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if ($this->identity()->getId() !== $user->getId()) {
            return $this->notFoundAction(); exit;
        }

        $gallery = new Gallery();
        $form = $this->annotationFormService->getAnnotationForm($gallery);
        $form->setValidationGroup(['file']); // form with one field

        $request = $this->getRequest();
        if ($request->isPost()) {
            $files = $request->getFiles()->toArray();

            if ($files) {
                $fileName = $files['file']['name'];
            }

            $form->setData($files);

            if ($form->isValid()) {
                $gallery = $form->getData();
                if ($fileName) {
                    $gallery->setImage('/img/gallery/' . $fileName);
                    $gallery->setUser($this->identity());
                }

                $this->entityManager->persist($gallery);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('gallery', ['action' => 'show', 'username' => $user->getUsername()]);
            }
        }

        $images = $this->galleryRepository->findBy(['user' => $user], ['id' => 'DESC']);

        return new ViewModel([
            'user'    => $user,
            'form'    => $form,
            'images'  => $images,
        ]);
    }

    public function deleteAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id', 0);
        $image = $this->galleryRepository->find($id);
        $request = $this->getRequest();

        if (! $image || ! $request->isPost()) {
            return $this->notFoundAction(); exit;
        }

        $username = $this->getEvent()->getRouteMatch()->getParam('username', '');
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if ($user) {
            if ($this->identity()->getId() !== $user->getId()) {
                return $this->notFoundAction(); exit;
            }
        }

        $this->entityManager->remove($image);
        $this->entityManager->flush();

        return $this->redirect()->toRoute('gallery', ['action' => 'manage', 'username' => $username]);
    }

    public function checkFriendship($user)
    {
        if ($user) {
            if ($this->identity()->getId() !== $user->getId()) {
                $result = $this->relationshipRepository->checkingFriendship($this->identity()->getId(), $user->getId());

                if (! $result) {
                    return $this->notFoundAction(); exit;
                }
            }
        }

        return true;
    }





}
