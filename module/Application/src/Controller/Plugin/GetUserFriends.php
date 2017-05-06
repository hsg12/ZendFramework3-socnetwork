<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\User;
use Application\Entity\Relationship;

class GetUserFriends extends AbstractPlugin
{
    public function __invoke(EntityManagerInterface $entityManager, User $user, $idsRequired = false)
    {
        $userFriends = $entityManager->getRepository(Relationship::class)
                                     ->getFriends($user->getId());

        if (! $userFriends) {
            $friends = []; // User have not friends
        } else {
            foreach ($userFriends as $value) {
                if ($user->getId() == $value->getUserOne()->getId()) {
                    $friends[] = $value->getUserTwo(); // if userOne, his friends
                } else {
                    $friends[] = $value->getUserOne(); // if userTwo, his friends
                }
            }
        }

        ///   If need to return friend's ids not friends   /////////////////////////
        if (is_array($friends) && $idsRequired) {
            $ids = '';
            foreach ($friends as $key => $friend) {
                if ($key < count($friends) - 1) {
                    $ids .= $friend->getId() . ', ';
                } else {
                    $ids .= $friend->getId();
                }
            }
            return $ids;
        } else {
            return $friends;
        }
    }
}
