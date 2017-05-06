<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\StatusLike;

class GetLikeCount extends AbstractHelper
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository    = $this->entityManager->getRepository(StatusLike::class);
    }

    public function __invoke($status, $likeStr = true)
    {
        $output = '';
        $statusCount = $this->repository->getLikeCount($status);

        if ($likeStr) {
            if (! $statusCount) {
                $output = '<span class="badge">0</span> &nbsp;Like';
            } elseif ($statusCount == 1) {
                $output = '<span class="badge">' . $statusCount . '</span> &nbsp;Like';
            } else {
                $output = '<span class="badge">' . $statusCount . '</span> &nbsp;Likes';
            }
            return $output;
        }

        if ($statusCount) {
            $output = '<span class="badge">' . $statusCount . '</span>';
        } else {
            $output = '<span class="badge">0</span>';
        }

        return $output;
    }
}
