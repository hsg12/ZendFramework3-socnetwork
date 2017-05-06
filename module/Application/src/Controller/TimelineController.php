<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Status;
use Application\Entity\StatusLike;
use Application\Entity\User;
use Application\Entity\Relationship;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class TimelineController extends AbstractActionController
{
    private $entityManager;
    private $statusRepository;
    private $relationshipRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager          = $entityManager;
        $this->statusRepository       = $this->entityManager->getRepository(Status::class);
        $this->relationshipRepository = $this->entityManager->getRepository(Relationship::class);
        $this->userRepository         = $this->entityManager->getRepository(User::class);
        $this->statusLikeRepository   = $this->entityManager->getRepository(StatusLike::class);
    }

    public function indexAction()
    {
        $status = new Status();
        $form = $this->getAnnotationForm($this->entityManager, $status);
        $form->setValidationGroup(['content']);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $status = $form->getData();
                $status->setUser($this->identity());

                $this->entityManager->persist($status);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('home');
            }
        }

        $hasFriends = $this->relationshipRepository
                           ->getFriends($this->identity()->getId());

        $paginator = $this->getUserStatuses($hasFriends);
        $pageNumber = (int)$paginator->getCurrentPageNumber();

        return new ViewModel([
            'form'       => $form,
            'replayForm' => $form,
            'statuses'   => $paginator,
            'hasFriends' => $hasFriends,
            'pageNumber' => $pageNumber,
        ]);
    }

    public function replayAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        $pageNumber = (int)$this->params()->fromRoute('page', 0);
        $status = new Status();
        $form = $this->getAnnotationForm($this->entityManager, $status);
        $form->setValidationGroup(['content']);

        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $content = $request->getPost()->toArray();
        $content = $this->clearString($content['replay-' . $id]);

        if (! empty($content)) {

            $status->setUser($this->identity());
            $status->setParentId($id);
            $status->setContent($content);

            $this->entityManager->persist($status);
            $this->entityManager->flush();
        }

        return $this->redirect()->toRoute('timeline', ['page' => $pageNumber]);
    }

    public function getUserStatuses($hasFriends)
    {
        //////   If user has friends only then show statuses   //////

        if ($hasFriends) {
            $statusesQuery = $this->statusRepository
                                  ->getUserStatuses($this->identity()->getId());

            $adapter = new DoctrinePaginator(new ORMPaginator($statusesQuery));
            $paginator = new Paginator($adapter);

            $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
            $paginator->setCurrentPageNumber($currentPageNumber);

            $itemCountPerPage = 10;
            $paginator->setItemCountPerPage($itemCountPerPage);
        } else {
            $paginator = false;
        }

        return $paginator;
    }

    public function addLikeAction()
    {
        $request  = $this->getRequest();
        $response = $this->getResponse();

        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $post = $this->request->getPost()->toArray();
        $statusId = $post['id'];
        $userId = $post['identity'];

        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $status = $this->statusRepository->findOneBy(['id' => $statusId]);

        $isLikeExists = $this->statusLikeRepository->findOneBy(['user' => $userId, 'status' => $statusId]);

        if ($isLikeExists) {
            $response->setContent(\Zend\Json\Json::encode(['alreadyLiked' => 'You already liked it']));
            return $response;
        }

        if ($user && $status && ! $isLikeExists) {
            $statusLike = new StatusLike();
            $statusLike->setUser($user);
            $statusLike->setStatus($status);

            $this->entityManager->persist($statusLike);
            $this->entityManager->flush();

            $statusCount = $this->statusLikeRepository->getLikeCount($status);

            $response->setContent(\Zend\Json\Json::encode(['statusCount' => $statusCount]));
        }

        return $response;
    }
}
